<x-guest-layout>
    <div class="w-full max-w-6xl mx-auto px-4" x-data="{ showExpenseModal: false, splitType: 'equal' }">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-1">Guest View</p>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white">{{ $event->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('M d, Y') : 'Date TBD' }}
                    &middot; Organized by <span class="font-semibold">{{ $event->creator->name }}</span>
                </p>
            </div>
            <a href="{{ route('login') }}"
                class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                Log in for full access
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Content (Debts & Expenses) -->
            <div class="md:col-span-2 space-y-6">

                @php
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
                                    <p class="font-bold text-gray-900 dark:text-white">
                                        {{ number_format($settlement->amount, 2) }} <span
                                            class="text-xs">{{ $event->currency }}</span></p>
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
                                Locked
                            </span>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse ($event->expenses as $expense)
                            <div
                                class="p-4 border border-gray-100 dark:border-gray-700 rounded-2xl flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="h-12 w-12 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="text-right">
                                    <p class="font-black text-lg text-gray-900 dark:text-white">
                                        {{ number_format($expense->total_amount, 2) }}</p>
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase">{{ $event->currency }}</p>
                                </div>
                            </div>
                        @empty
                            <div
                                class="text-center py-10 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
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
                    <form action="{{ route('guest.expenses.store', $event->guest_token) }}" method="POST">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-6 sm:p-8">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-2xl leading-6 font-black text-gray-900 dark:text-white mb-6"
                                        id="modal-title">New Expense</h3>

                                    <div class="space-y-5">
                                        <div>
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">What
                                                was it for?</label>
                                            <input type="text" name="description" required
                                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 rounded-xl shadow-sm px-4 py-3"
                                                placeholder="e.g. Dinner, Uber, Tickets">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Total
                                                Amount ({{ $event->currency }})</label>
                                            <input type="number" step="0.01" name="total_amount" required
                                                class="w-full font-black text-2xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 rounded-2xl shadow-sm px-5 py-4 bg-gray-50 text-brand-600 dark:text-brand-400"
                                                placeholder="0.00">
                                        </div>

                                        <div
                                            class="w-full bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                                Who Paid? <span class="text-xs font-normal text-gray-500 ml-2">(Enter
                                                    amounts. Sum must equal the total above)</span>
                                            </label>
                                            <div class="space-y-3 max-h-40 overflow-y-auto pr-2">
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

                                                        <div x-show="splitType === 'custom'" class="w-1/3">
                                                            <input type="hidden"
                                                                name="amounts[{{ $loop->index }}][participant_id]"
                                                                value="{{ $participant->id }}">
                                                            <input type="number" step="0.01"
                                                                name="amounts[{{ $loop->index }}][amount]"
                                                                class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg px-3 py-1.5 focus:border-brand-500 focus:ring-brand-500"
                                                                placeholder="0.00">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
</x-guest-layout>
