<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $event->name }}
            </h2>
            <div class="text-sm text-gray-500">
                {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('M d, Y') : '' }}
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ showExpenseModal: false, splitType: 'equal', customAmounts: {}, payers: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-indigo-100 border border-indigo-400 text-indigo-700 px-4 py-3 rounded-2xl relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl relative"
                    role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Content (Expenses & Debts) -->
                <div class="md:col-span-2 space-y-6">

                    <!-- Debts / Settlements Card -->
                    @php
                        // Fetch calculated pending and paid settlements
                        $settlements = \App\Models\Settlement::where('event_id', $event->id)
                            ->whereIn('status', [
                                \App\Models\Settlement::STATUS_PENDING,
                                \App\Models\Settlement::STATUS_PAID,
                            ])
                            ->with(['fromParticipant.user', 'toParticipant.user'])
                            ->get();
                    @endphp
                    @if ($settlements->count() > 0)
                        <div
                            class="bg-gradient-to-br from-red-50 dark:from-red-900/10 to-orange-50 dark:to-orange-900/10 rounded-3xl p-6 border border-red-100 dark:border-red-900/20">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Who Owes Whom
                            </h3>
                            <div class="space-y-3">
                                @foreach ($settlements as $settlement)
                                    @php
                                        $fromName =
                                            $settlement->fromParticipant->user->name ??
                                            $settlement->fromParticipant->guest_name;
                                        $toName =
                                            $settlement->toParticipant->user->name ??
                                            $settlement->toParticipant->guest_name;
                                        $isDebtor =
                                            auth()->check() && $settlement->fromParticipant->user_id === auth()->id();
                                        $isCreditor =
                                            auth()->check() && $settlement->toParticipant->user_id === auth()->id();
                                    @endphp
                                    <div
                                        class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex -space-x-2">
                                                <div
                                                    class="h-8 w-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs ring-2 ring-white dark:ring-gray-800 z-10">
                                                    {{ substr($fromName, 0, 1) }}</div>
                                                <div
                                                    class="h-8 w-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-xs ring-2 ring-white dark:ring-gray-800">
                                                    {{ substr($toName, 0, 1) }}</div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white"><span
                                                        class="font-bold">{{ explode(' ', $fromName)[0] }}</span> owes
                                                    <span class="font-bold">{{ explode(' ', $toName)[0] }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <p class="font-bold text-gray-900 dark:text-white">
                                                {{ number_format($settlement->amount, 2) }} <span
                                                    class="text-xs">EGP</span></p>

                                            @if ($isDebtor && $settlement->status === \App\Models\Settlement::STATUS_PENDING)
                                                <form action="{{ route('settlements.pay', $settlement) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-brand-600 text-white rounded-lg text-xs font-semibold hover:bg-brand-700 transition">
                                                        {{ $settlement->toParticipant->user && $settlement->toParticipant->user->instapay_link ? 'Pay Via InstaPay' : 'Mark as Paid' }}
                                                    </button>
                                                </form>
                                            @elseif($isDebtor && $settlement->status === \App\Models\Settlement::STATUS_PAID)
                                                <span
                                                    class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-semibold">Pending
                                                    Confirmation</span>
                                            @elseif($isCreditor && $settlement->status === \App\Models\Settlement::STATUS_PAID)
                                                <form action="{{ route('settlements.confirm', $settlement) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 transition">Confirm
                                                        Receipt</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-3xl border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Activity Timeline</h3>
                            @if (!$event->isLocked())
                                <button @click="showExpenseModal = true"
                                    class="px-5 py-2.5 bg-brand-600 rounded-full font-bold text-sm text-white hover:bg-brand-700 shadow-md transform hover:scale-105 transition-all">
                                    + Add Expense
                                </button>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                    Locked
                                </span>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @forelse($event->expenses()->latest()->get() as $expense)
                                <div
                                    class="p-4 border border-gray-100 dark:border-gray-700 hover:border-brand-200 dark:hover:border-brand-800 rounded-2xl flex items-center justify-between transition group">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="h-12 w-12 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-400 group-hover:bg-brand-50 group-hover:text-brand-600 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white">
                                                {{ $expense->description }}</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">Paid by
                                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                                    @foreach ($expense->payers as $payer)
                                                        {{ explode(' ', $payer->participant->user->name ?? $payer->participant->guest_name)[0] }}
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right flex flex-col items-end space-y-2">
                                        <div>
                                            <p class="font-black text-lg text-gray-900 dark:text-white">
                                                {{ number_format($expense->total_amount, 2) }}</p>
                                            <p class="text-[10px] text-gray-400 font-semibold uppercase">EGP</p>
                                        </div>
                                        @if (
                                            !$event->isLocked() &&
                                                (auth()->id() === $event->creator_id || $expense->payers->contains('participant.user_id', auth()->id())))
                                            <div class="flex items-center space-x-2 mt-2">
                                                <a href="{{ route('expenses.edit', [$event, $expense]) }}"
                                                    class="text-blue-500 hover:text-blue-700 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition cursor-pointer"
                                                    title="Edit Expense">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('expenses.destroy', [$event, $expense]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this expense? Debts will be recalculated.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition cursor-pointer"
                                                        title="Delete Expense">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="text-center py-10 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No expenses yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Participants & Share Link) -->
                <div class="space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-3xl border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">
                            Invite Friends</h3>
                        <div
                            class="flex items-center p-3 text-sm bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                            <input type="text" readonly value="{{ url('/e/' . $event->guest_token) }}"
                                class="bg-transparent border-none w-full text-gray-700 dark:text-gray-300 focus:ring-0 px-0" />
                            <button
                                onclick="navigator.clipboard.writeText('{{ url('/e/' . $event->guest_token) }}'); alert('Link copied!')"
                                class="text-brand-600 hover:text-brand-800 dark:text-brand-400 font-bold ml-3 px-2 py-1 bg-brand-50 dark:bg-brand-900/30 rounded-lg transition text-xs">Copy</button>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-3xl border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">
                            Group Members <span
                                class="text-gray-400 font-normal">({{ $event->participants->count() }})</span></h3>
                        <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-3 gap-y-4 gap-x-2">
                            @foreach ($event->participants as $participant)
                                <div class="flex flex-col items-center group">
                                    <div
                                        class="h-12 w-12 bg-gradient-to-tr from-brand-400 to-purple-500 rounded-full flex items-center justify-center font-bold text-white shadow-sm ring-2 ring-transparent group-hover:ring-brand-300 transition cursor-pointer">
                                        {{ substr($participant->user->name ?? $participant->guest_name, 0, 1) }}
                                    </div>
                                    <p
                                        class="text-[10px] font-semibold text-gray-600 dark:text-gray-400 text-center truncate w-full mt-1.5 px-0.5">
                                        {{ explode(' ', $participant->user->name ?? $participant->guest_name)[0] }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Add Expense Modal (AlpineJS) -->
        <div x-show="showExpenseModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showExpenseModal" x-transition.opacity
                    class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                    @click="showExpenseModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showExpenseModal" x-transition.scale
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                    <form action="{{ route('expenses.store', $event) }}" method="POST">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-6 sm:p-8">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-2xl leading-6 font-black text-gray-900 dark:text-white"
                                            id="modal-title">New Expense</h3>
                                    </div>

                                    <div class="space-y-5">
                                        <!-- Description -->
                                        <div>
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">What
                                                was it for?</label>
                                            <input type="text" name="description" required
                                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 rounded-xl shadow-sm px-4 py-3"
                                                placeholder="e.g. Dinner, Uber, Tickets">
                                        </div>

                                        <!-- Total Amount -->
                                        <div>
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Total
                                                Amount (EGP)</label>
                                            <input type="number" step="0.01" name="total_amount" required
                                                class="w-full font-black text-2xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 rounded-2xl shadow-sm px-5 py-4 bg-gray-50 text-brand-600 dark:text-brand-400"
                                                placeholder="0.00">
                                        </div>

                                        <!-- Who Paid -->
                                        <div
                                            class="w-full bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Who Paid? <span class="text-xs font-normal text-gray-500 ml-2">(Enter
                                                    amounts. Sum must equal the total above)</span>
                                            </label>
                                            <div class="space-y-3 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                                                @foreach ($event->participants as $index => $participant)
                                                    <div
                                                        class="flex items-center justify-between bg-white dark:bg-gray-800 p-2.5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                                        <div class="flex items-center space-x-3">
                                                            <div
                                                                class="h-8 w-8 rounded-full bg-gradient-to-tr from-brand-400 to-purple-500 text-white flex items-center justify-center text-xs font-bold">
                                                                {{ substr($participant->user->name ?? $participant->guest_name, 0, 1) }}
                                                            </div>
                                                            <span
                                                                class="text-sm font-bold text-gray-900 dark:text-white">{{ explode(' ', $participant->user->name ?? $participant->guest_name)[0] }}</span>
                                                        </div>
                                                        <div class="w-1/3 relative">
                                                            <input type="hidden"
                                                                name="payers[{{ $index }}][participant_id]"
                                                                value="{{ $participant->id }}">
                                                            <input type="number" step="0.01"
                                                                name="payers[{{ $index }}][amount]"
                                                                class="w-full text-sm font-bold border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-1.5 focus:border-brand-500 focus:ring-brand-500 text-right pr-2"
                                                                placeholder="0.00">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Split Options -->
                                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">How
                                                to split?</label>

                                            <div
                                                class="flex space-x-2 bg-gray-100 dark:bg-gray-900 p-1 rounded-xl mb-4">
                                                <button type="button" @click="splitType = 'equal'"
                                                    :class="{ 'bg-white dark:bg-gray-700 shadow-sm text-brand-600 dark:text-brand-400': splitType === 'equal', 'text-gray-500': splitType !== 'equal' }"
                                                    class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Equally</button>
                                                <button type="button" @click="splitType = 'custom'"
                                                    :class="{ 'bg-white dark:bg-gray-700 shadow-sm text-brand-600 dark:text-brand-400': splitType === 'custom', 'text-gray-500': splitType !== 'custom' }"
                                                    class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Custom
                                                    Amounts</button>
                                            </div>
                                            <input type="hidden" name="split_type" :value="splitType">

                                            <!-- Participants List for Split -->
                                            <div class="space-y-3 max-h-48 overflow-y-auto pr-2">
                                                @foreach ($event->participants as $participant)
                                                    <div class="flex items-center justify-between">
                                                        <label class="flex items-center space-x-3 cursor-pointer">
                                                            <input type="checkbox" name="participants[]"
                                                                value="{{ $participant->id }}" checked
                                                                class="form-checkbox h-5 w-5 text-brand-600 rounded border-gray-300 focus:ring-brand-500">
                                                            <span
                                                                class="text-sm font-medium text-gray-900 dark:text-white">{{ explode(' ', $participant->user->name ?? $participant->guest_name)[0] }}</span>
                                                        </label>

                                                        <!-- Custom Amount Input -->
                                                        <div x-show="splitType === 'custom'" class="w-1/3">
                                                            <input type="hidden"
                                                                name="amounts[{{ $loop->index }}][participant_id]"
                                                                value="{{ $participant->id }}">
                                                            <div class="relative">
                                                                <input type="number" step="0.01"
                                                                    name="amounts[{{ $loop->index }}][amount]"
                                                                    class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg px-3 py-1.5 focus:border-brand-500 focus:ring-brand-500"
                                                                    placeholder="0.00">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of modal white content area -->

                        <!-- Modal action buttons -->
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-3xl">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-brand-600 text-base font-bold text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Save Expense
                            </button>
                            <button type="button" @click="showExpenseModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-6 py-3 bg-white dark:bg-gray-800 text-base font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
