<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>

        <!-- Cropper.js CDN -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-8" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Avatar Upload Section using Alpine.js for preview & Cropper -->
        <div x-data="avatarCropper()" class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 pb-6 border-b border-gray-100 dark:border-gray-800/50 relative z-20">
            <div class="relative group cursor-pointer">
                <!-- Current or Preview Avatar -->
                <div class="relative h-24 w-24 sm:h-32 sm:w-32 rounded-full overflow-hidden bg-white dark:bg-gray-800 p-1 shadow-lg border border-gray-200 dark:border-gray-700">
                    <template x-if="finalImageUrl">
                        <img :src="finalImageUrl" class="h-full w-full object-cover rounded-full" alt="Avatar">
                    </template>
                    <template x-if="!finalImageUrl">
                        <div class="h-full w-full bg-gradient-to-br from-brand-100 to-brand-50 dark:from-gray-800 dark:to-gray-900 rounded-full flex items-center justify-center">
                            @if($user->avatar)
                                <img src="{{ $user->getAvatarUrl() }}" class="h-full w-full object-cover rounded-full" alt="Avatar">
                            @else
                                <span class="text-4xl font-black bg-clip-text text-transparent bg-gradient-to-br from-brand-500 to-accent-600">{{ substr($user->name, 0, 1) }}</span>
                            @endif
                        </div>
                    </template>
                </div>

                <!-- Hover Overlay for Upload -->
                <div @click="$refs.avatarInput.click()" class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
            </div>

            <div class="text-center sm:text-left">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Profile Picture</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-3">JPG, PNG or WEBP. Max size of 2MB.</p>
                <div class="flex flex-wrap gap-3 justify-center sm:justify-start">
                    <button type="button" @click="$refs.avatarInput.click()" class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 dark:focus:ring-offset-gray-900">
                        Upload Image
                    </button>
                    <!-- Hidden File Input for browsing -->
                    <input type="file" x-ref="avatarInput" @change="fileChosen" class="hidden" accept="image/png, image/jpeg, image/webp" />
                    <!-- Hidden input to send cropped base64 directly to server -->
                    <input type="hidden" name="avatar_base64" :value="finalImageUrl" />
                </div>
                <!-- Validation Error (if it comes from server) -->
                @error('avatar_base64')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cropper Modal -->
            <div x-show="cropModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-6 w-full max-w-lg max-h-screen overflow-hidden flex flex-col" @click.outside="cropModalOpen = false">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Crop your new profile picture</h3>
                    <div class="w-full h-80 bg-gray-100 dark:bg-gray-900 rounded-2xl overflow-hidden shadow-inner">
                        <img x-ref="cropperImage" src="" class="max-w-full hidden" alt="Picture to crop">
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="cropModalOpen = false" class="px-5 py-2.5 rounded-xl font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Cancel</button>
                        <button type="button" @click="saveCrop" class="px-5 py-2.5 rounded-xl font-bold bg-brand-500 hover:bg-brand-600 text-white transition shadow-sm">Save Avatar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function avatarCropper() {
                return {
                    cropModalOpen: false,
                    rawImageUrl: null,
                    finalImageUrl: null,
                    cropper: null,
                    
                    fileChosen(event) {
                        if (! event.target.files.length) return;
                        let file = event.target.files[0];
                        let reader = new FileReader();

                        reader.onload = e => {
                            this.rawImageUrl = e.target.result;
                            this.$refs.cropperImage.src = this.rawImageUrl;
                            this.cropModalOpen = true;
                            
                            // Initialize Cropper inside next tick after modal shows
                            this.$nextTick(() => {
                                if(this.cropper) { this.cropper.destroy(); }
                                
                                this.$refs.cropperImage.classList.remove('hidden');
                                this.cropper = new Cropper(this.$refs.cropperImage, {
                                    aspectRatio: 1,
                                    viewMode: 1,
                                    guides: false,
                                    autoCropArea: 1,
                                    background: false,
                                });
                            });
                        };
                        reader.readAsDataURL(file);
                        
                        // Clear the input so selecting the same file triggers change again
                        event.target.value = '';
                    },
                    
                    saveCrop() {
                        if (!this.cropper) return;
                        
                        // Get cropped canvas
                        let canvas = this.cropper.getCroppedCanvas({
                            width: 256,
                            height: 256,
                            imageSmoothingEnabled: true,
                            imageSmoothingQuality: 'high',
                        });
                        
                        // Convert to webp base64 format for size constraints and quality
                        this.finalImageUrl = canvas.toDataURL('image/webp', 0.9);
                        this.cropModalOpen = false;
                        this.cropper.destroy();
                        this.cropper = null;
                        this.$refs.cropperImage.src = '';
                        this.$refs.cropperImage.classList.add('hidden');
                    }
                }
            }
        </script>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Social & Financial Links (Using Fintech styled inputs) -->
        <div class="pt-6 border-t border-gray-100 dark:border-gray-800/50">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight mb-4">Connect Social & Financial Apps</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Phone Number -->
                <div>
                    <x-input-label for="phone_number" :value="__('Phone Number')" class="font-bold mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </div>
                        <x-text-input id="phone_number" style="padding-left: 2.75rem" name="phone_number" type="text" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-800/50 dark:border-gray-700/50 dark:focus:bg-gray-800" :value="old('phone_number', $user->phone_number)" placeholder="+20 100..." autocomplete="tel" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                </div>

                <!-- WhatsApp -->
                <div>
                    <x-input-label for="whatsapp" :value="__('WhatsApp Number')" class="font-bold mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </div>
                        <x-text-input id="whatsapp" style="padding-left: 2.75rem" name="whatsapp" type="text" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-800/50 dark:border-gray-700/50 dark:focus:bg-gray-800" :value="old('whatsapp', $user->whatsapp)" placeholder="+20 100..." autocomplete="tel" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
                </div>

                <!-- InstaPay -->
                <div class="sm:col-span-2">
                    <x-input-label for="instapay_link" :value="__('InstaPay Link / Username')" class="font-bold mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <div class="w-7 h-7 rounded bg-purple-600 flex items-center justify-center text-white text-[10px] font-black tracking-tighter">IP</div>
                        </div>
                        <x-text-input id="instapay_link" style="padding-left: 3rem" name="instapay_link" type="text" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-800/50 dark:border-gray-700/50 dark:focus:bg-gray-800" :value="old('instapay_link', $user->instapay_link)" placeholder="yourname@instapay" />
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Used to automatically settle debts quickly with friends.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('instapay_link')" />
                </div>

                <!-- Instagram -->
                <div>
                    <x-input-label for="instagram" :value="__('Instagram Profile')" class="font-bold mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-pink-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </div>
                        <x-text-input id="instagram" style="padding-left: 2.75rem" name="instagram" type="text" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-800/50 dark:border-gray-700/50 dark:focus:bg-gray-800" :value="old('instagram', $user->instagram)" placeholder="@username" autocomplete="url" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('instagram')" />
                </div>

                <!-- Facebook -->
                <div>
                    <x-input-label for="facebook" :value="__('Facebook Profile')" class="font-bold mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </div>
                        <x-text-input id="facebook" style="padding-left: 2.75rem" name="facebook" type="text" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-800/50 dark:border-gray-700/50 dark:focus:bg-gray-800" :value="old('facebook', $user->facebook)" placeholder="Profile Link" autocomplete="url" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('facebook')" />
                </div>

                <!-- Snapchat -->
                <div>
                    <x-input-label for="snapchat" :value="__('Snapchat')" class="font-bold mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M23.906 17.51a3.037 3.037 0 01-1.353-2.164 1.341 1.341 0 00-.773-1.077c-.456-.22-.924-.46-1.577-.663-.2-.062-.317-.189-.286-.426a10.949 10.949 0 001.318-3.056c.219-.77.34-1.748.243-2.673a5.539 5.539 0 00-2.315-3.86C17.151 2.21 14.86.375 12.046.22 9.172.064 6.645 1.701 4.772 3.593A5.558 5.558 0 002.531 7.458c-.097.925.025 1.903.243 2.673a10.957 10.957 0 001.318 3.056c.03.237-.087.363-.286.426-.653.203-1.12.443-1.577.663a1.342 1.342 0 00-.773 1.077 3.033 3.033 0 01-1.353 2.164.717.717 0 00.183 1.393c.125 0 .285-.028.468-.073.434-.108 1.055-.262 1.765-.36.43-.06.879-.115 1.309-.16.29-.03.566-.057.8-.078a.488.488 0 01.52.336 4.774 4.774 0 001.123 1.832c1.373 1.374 3.42 2.106 5.767 2.106 2.348 0 4.394-.732 5.767-2.106a4.773 4.773 0 001.123-1.832.488.488 0 01.52-.336c.234.02.51.048.8.077.43.045.879.1 1.308.16.71.098 1.332.252 1.765.36.183.045.343.073.468.073a.717.717 0 00.183-1.393zM12 21.054c-1.921 0-3.578-.6-4.66-1.681a3.298 3.298 0 01-.77-1.258.113.113 0 00-.091-.072c-.158-.016-.328-.035-.503-.053-.306-.032-.614-.064-.881-.082a3.843 3.843 0 01-.986-.184l-.195-.067c.361-.2.724-.448 1.084-.69.458-.309.91-.614 1.255-.838a1.275 1.275 0 00.584~-1.58.629.629 0 00-.606-.395H5.808c.552-.3 1.066-.549 1.487-.728a4.912 4.912 0 001.077-.6.425.425 0 00.126-.454c-.167-.403-.314-1.096-.403-1.748a8.312 8.312 0 01-.065-2.003c.123-.974.887-2.615 1.821-3.692C11.538 4.417 12 4.425 12 4.425s.462-.008 2.15 1.109c.934 1.077 1.698 2.718 1.821 3.692a8.312 8.312 0 01-.065 2.003c-.089.652-.236 1.345-.403 1.748a.425.425 0 00.126.454 4.914 4.914 0 001.077.6c.421.179.935.428 1.487.728h-.441a.63.63 0 00-.606.395 1.275 1.275 0 00.584 1.58c.345.224.797.529 1.255.838.36.242.723.49 1.084.69l-.195.067a3.816 3.816 0 01-.986.184c-.267.018-.575.05-.881.082-.175.018-.345.037-.503.053a.112.112 0 00-.091.072 3.3 3.3 0 01-.77 1.258c-1.082 1.081-2.739 1.681-4.66 1.681z"/></svg>
                        </div>
                        <x-text-input id="snapchat" style="padding-left: 2.75rem" name="snapchat" type="text" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-800/50 dark:border-gray-700/50 dark:focus:bg-gray-800" :value="old('snapchat', $user->snapchat)" placeholder="@username" autocomplete="url" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('snapchat')" />
                </div>

                <!-- TikTok -->
                <div>
                    <x-input-label for="tiktok" :value="__('TikTok')" class="font-bold mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 2.78-1.15 5.54-3.33 7.31-1.92 1.57-4.67 2.15-7.05 1.44-2.41-.7-4.44-2.58-5.32-4.9-1.16-2.92-.37-6.27 1.95-8.46 2.12-2 5.09-2.78 7.84-2.19v4.13c-1.92-.5-4.14-.14-5.59 1.35-1.07 1.09-1.46 2.78-.96 4.19.46 1.34 1.76 2.42 3.19 2.6 1.66.21 3.42-.42 4.41-1.74.82-1.07 1.08-2.45 1.02-3.79-.05-3.3-.01-6.61-.03-9.92-.02-2.31 0-5.63-.05-7.94z"/></svg>
                        </div>
                        <x-text-input id="tiktok" style="padding-left: 2.75rem" name="tiktok" type="text" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-800/50 dark:border-gray-700/50 dark:focus:bg-gray-800" :value="old('tiktok', $user->tiktok)" placeholder="@username" autocomplete="url" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('tiktok')" />
                </div>
                
                <!-- Bio -->
                <div class="sm:col-span-2">
                    <x-input-label for="bio" :value="__('About You (Bio)')" class="font-bold mb-1" />
                    <textarea id="bio" name="bio" rows="3" class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-800 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 rounded-xl shadow-sm transition duration-200 outline-none resize-none" placeholder="Write a short bio about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Will be shown on your public profile when friends add you.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                </div>
            </div>
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
