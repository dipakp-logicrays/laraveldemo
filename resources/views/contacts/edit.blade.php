
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($contact) ? 'Edit' : 'Create' }} {{ __('Contact') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h1 class="text-xl font-semibold mb-4">{{ isset($contact) ? 'Edit' : 'Create' }} Contact</h1>
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('contacts.update', $contact) }}">
                        @include('contacts._form', ['contact' => $contact])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
