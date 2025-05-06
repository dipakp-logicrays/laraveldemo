<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('FAQs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">

                {{-- Search Bar --}}
                <form method="GET" action="{{ route('faqs.index') }}" class="mb-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request()->search }}"
                        placeholder="Search FAQs..."
                        class="border p-2 rounded w-1/2"
                    >
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded ml-3">
                        Search
                    </button>
                </form>

                {{-- Table --}}
                <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Question</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Answer</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($faqs as $faq)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $faq->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $faq->question }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $faq->answer }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No FAQs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $faqs->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
