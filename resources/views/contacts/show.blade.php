<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 bg-white sm:rounded-lg">
                        <h1 class="text-xl font-semibold mb-4"></h1>
                        <p><strong>Name:</strong> {{ $contact->name }}</p>
                        <p><strong>Phone:</strong> {{ $contact->phone }}</p>
                        <p><strong>Email:</strong> {{ $contact->email }}</p>
                        <p><strong>Description:</strong> {{ $contact->description }}</p>
                        @if($contact->attachment)
                            <img src="{{ asset('storage/' . $contact->attachment) }}" class="w-16">
                        @endif
                        <br/>
                        <a href="{{ route('contacts.index') }}" class="mt-4 inline-block bg-gray-500 text-white px-4 py-2 rounded mt-4">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
