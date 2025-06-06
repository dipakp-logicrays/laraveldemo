<x-app-layout>
    <x-slot name="header">
        <div class="text-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Frequently Asked Questions') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">Find answers to common questions</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Section -->
            <div class="mb-8">
                <form method="GET" action="{{ route('faqs.index') }}" class="relative">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text"
                               name="search"
                               id="search"
                               value="{{ request()->search }}"
                               placeholder="Search FAQs..."
                               class="block w-full pl-10 pr-20 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                        <div class="absolute inset-y-0 right-0 flex items-center">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 mr-1 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Search
                            </button>
                        </div>
                    </div>

                    @if(request()->has('search'))
                        <div class="mt-3 flex items-center">
                            <span class="text-sm text-gray-600">
                                Showing results for "<strong>{{ request()->search }}</strong>"
                            </span>
                            <a href="{{ route('faqs.index') }}"
                               class="ml-3 text-sm text-indigo-600 hover:text-indigo-900">
                                Clear search
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- FAQ Section -->
            @if($faqs->isEmpty())
                <!-- Empty State -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No FAQs found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request()->has('search'))
                                Try adjusting your search terms
                            @else
                                No frequently asked questions available at the moment
                            @endif
                        </p>
                    </div>
                </div>
            @else
                <!-- FAQ Stats -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total FAQs</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $faqs->total() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Answered Questions</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $faqs->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Categories</p>
                                <p class="text-2xl font-semibold text-gray-900">5</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Accordion -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach($faqs as $index => $faq)
                            <div x-data="{ open: false }" class="transition-all duration-200">
                                <button @click="open = !open"
                                        class="w-full px-6 py-4 text-left hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition-colors duration-150">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-start">
                                            <span class="flex-shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 text-sm font-medium mr-3">
                                                {{ $index + 1 + (($faqs->currentPage() - 1) * $faqs->perPage()) }}
                                            </span>
                                            <h3 class="text-base font-medium text-gray-900 pr-4">
                                                {{ $faq->question }}
                                            </h3>
                                        </div>
                                        <span class="ml-6 flex-shrink-0">
                                            <svg class="h-5 w-5 text-gray-400 transform transition-transform duration-200"
                                                 :class="{ 'rotate-180': open }"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                                     class="px-6 pb-4"
                                     style="display: none;">
                                    <div class="pl-11 pr-4">
                                        <p class="text-gray-600 leading-relaxed">
                                            {{ $faq->answer }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                @if($faqs->hasPages())
                    <div class="mt-6">
                        {{ $faqs->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif

            <!-- Help Section -->
            <div class="mt-8 bg-indigo-50 rounded-lg p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Still need help?</h3>
                <p class="mt-1 text-sm text-gray-600">Can't find what you're looking for? Our support team is here to help.</p>
                <div class="mt-4">
                    <a href="{{ route('contacts.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
