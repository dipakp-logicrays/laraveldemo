<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Contact') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('contacts.show', $contact) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Contact
                </a>
                <a href="{{ route('contacts.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Contacts
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Contact Information
                    </h3>
                    <p class="text-yellow-100 text-sm mt-1">Update the contact details below</p>
                </div>

                <!-- Current Contact Info Banner -->
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                    <div class="flex items-center">
                        @if($contact->attachment)
                            <img src="{{ asset('storage/' . $contact->attachment) }}"
                                 alt="{{ $contact->name }}"
                                 class="w-12 h-12 rounded-full object-cover mr-3">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                <span class="text-gray-600 font-medium">
                                    {{ strtoupper(substr($contact->name, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">Currently editing:</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $contact->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="p-6">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('contacts.update', $contact) }}" class="space-y-6">
                        @include('contacts._form', ['contact' => $contact])
                    </form>
                </div>
            </div>

            <!-- Last Modified Info -->
            <div class="mt-6 text-center text-sm text-gray-600">
                Last updated {{ optional($contact->updated_at)->diffForHumans() ?? 'never' }}
            </div>
        </div>
    </div>
</x-app-layout>
