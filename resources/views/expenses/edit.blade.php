<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Expense: {{ $expense->description }}
            </h2>
            <a href="{{ route('events.show', $event) }}" class="text-sm text-brand-600 hover:underline">
                Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        splitType: '{{ $expense->splits->count() > 0 && $expense->splits->first()->amount != $expense->splits->last()->amount ? 'custom' : 'equal' }}'
    }">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm rounded-3xl overflow-hidden">
                <form action="{{ route('expenses.update', [$event, $expense]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 sm:p-8 space-y-6">
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl mb-4">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Description -->
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Description</label>
                            <input type="text" name="description" required
                                value="{{ old('description', $expense->description) }}"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 rounded-xl shadow-sm px-4 py-3">
                        </div>

                        <!-- Total Amount -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Total Amount
                                ({{ $event->currency }})</label>
                            <input type="number" step="0.01" name="total_amount" required
                                value="{{ old('total_amount', $expense->total_amount) }}"
                                class="w-full font-black text-2xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 rounded-2xl shadow-sm px-5 py-4 bg-gray-50 text-brand-600 dark:text-brand-400">
                        </div>

                        <!-- Payers -->
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <label
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Who Paid? <span class="text-xs font-normal text-gray-500 ml-2">(Enter amounts. Sum must
                                    equal the total above)</span>
                            </label>

                            <div class="space-y-3">
                                @foreach ($event->participants as $index => $participant)
                                    @php
                                        $existingPayer = $expense->payers->firstWhere(
                                            'participant_id',
                                            $participant->id,
                                        );
                                        $payerAmount = $existingPayer ? $existingPayer->amount : '';
                                    @endphp
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
                                            <input type="hidden" name="payers[{{ $index }}][participant_id]"
                                                value="{{ $participant->id }}">
                                            <input type="number" step="0.01"
                                                name="payers[{{ $index }}][amount]"
                                                value="{{ old('payers.' . $index . '.amount', $payerAmount) }}"
                                                class="w-full text-sm font-bold border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-1.5 focus:border-brand-500 focus:ring-brand-500 text-right pr-2"
                                                placeholder="0.00">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Split Option -->
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">How to
                                split?</label>

                            <div class="flex space-x-2 bg-gray-100 dark:bg-gray-900 p-1 rounded-xl mb-4">
                                <button type="button" @click="splitType = 'equal'"
                                    :class="{ 'bg-white dark:bg-gray-700 shadow-sm text-brand-600 dark:text-brand-400': splitType === 'equal', 'text-gray-500': splitType !== 'equal' }"
                                    class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Equally</button>
                                <button type="button" @click="splitType = 'custom'"
                                    :class="{ 'bg-white dark:bg-gray-700 shadow-sm text-brand-600 dark:text-brand-400': splitType === 'custom', 'text-gray-500': splitType !== 'custom' }"
                                    class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Custom
                                    Amounts</button>
                            </div>
                            <input type="hidden" name="split_type" :value="splitType">

                            <div class="space-y-3">
                                @foreach ($event->participants as $participant)
                                    @php
                                        $existingSplit = $expense->splits->firstWhere(
                                            'participant_id',
                                            $participant->id,
                                        );
                                        $splitAmount = $existingSplit ? $existingSplit->amount : '';
                                        $isChecked = $existingSplit ? 'checked' : '';
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="checkbox" name="participants[]" value="{{ $participant->id }}"
                                                {{ $isChecked }}
                                                class="form-checkbox h-5 w-5 text-brand-600 rounded border-gray-300 focus:ring-brand-500">
                                            <span
                                                class="text-sm font-medium text-gray-900 dark:text-white">{{ explode(' ', $participant->user->name ?? $participant->guest_name)[0] }}</span>
                                        </label>

                                        <div x-show="splitType === 'custom'" class="w-1/3">
                                            <input type="hidden" name="amounts[{{ $loop->index }}][participant_id]"
                                                value="{{ $participant->id }}">
                                            <input type="number" step="0.01"
                                                name="amounts[{{ $loop->index }}][amount]"
                                                value="{{ old('amounts.' . $loop->index . '.amount', $splitAmount) }}"
                                                class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg px-3 py-1.5 focus:border-brand-500 focus:ring-brand-500"
                                                placeholder="0.00">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 flex flex-row-reverse rounded-b-3xl">
                        <button type="submit"
                            class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-brand-600 text-base font-bold text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
                            Update Expense
                        </button>
                        <a href="{{ route('events.show', $event) }}"
                            class="mr-3 inline-flex justify-center rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-6 py-3 bg-white dark:bg-gray-800 text-base font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
