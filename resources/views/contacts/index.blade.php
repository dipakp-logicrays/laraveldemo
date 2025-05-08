<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contacts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <a href="{{ route('contacts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-4 ml-4">Add Contact</a>

                <form method="GET" action="{{ route('contacts.index') }}" class="mb-4 ml-4 mt-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request()->search }}"
                        placeholder="Search Contact by Name or Email..."
                        class="border p-2 rounded w-1/2"
                    >
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded ml-3">
                        Search
                    </button>
                </form>

                <table class="w-full mt-4 border">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2 text-left">Name</th>
                            <th class="border px-4 py-2 text-left">Phone</th>
                            <th class="border px-4 py-2 text-left">Email</th>
                            <th class="border px-4 py-2 text-left">Attachment</th>
                            <th class="border px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                        <tr>
                            <td class="border px-4 py-2">{{ $contact->name }}</td>
                            <td class="border px-4 py-2">{{ $contact->phone }}</td>
                            <td class="border px-4 py-2">{{ $contact->email }}</td>
                            <td class="border px-4 py-2">
                                @if($contact->attachment)
                                    <img src="{{ asset('storage/' . $contact->attachment) }}" class="w-16">
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('contacts.show', $contact) }}" class="text-blue-600">View</a>
                                    <a href="{{ route('contacts.edit', $contact) }}" class="text-yellow-600">Edit</a>
                                    <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600" onclick="return confirm('Delete this contact?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $contacts->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

