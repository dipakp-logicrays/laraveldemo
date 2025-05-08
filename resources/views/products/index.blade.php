<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product List') }}
        </h2>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 30px;
                background-color: #f9f9f9;
            }
            h1 {
                text-align: center;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                background-color: #fff;
                margin-top: 20px;
            }
            th, td {
                padding: 12px 15px;
                border: 1px solid #ccc;
                text-align: left;
            }
            th {
                background-color: #f0f0f0;
            }
            tr:nth-child(even) {
                background-color: #f8f8f8;
            }
        </style>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                @if ($products->isEmpty())
                    <p>No products found.</p>
                @else
                {{-- Search Bar --}}
                <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request()->search }}"
                        placeholder="Search Product..."
                        class="border p-2 rounded w-1/2"
                    >
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded ml-3">
                        Search
                    </button>
                </form>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>SKU</th>
                                <th>Description</th>
                                <th>Price ($)</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Active?</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($product->description, 50) }}</td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>{{ ucfirst($product->status) }}</td>
                                    <td>{{ $product->is_active ? 'Yes' : 'No' }}</td>
                                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
