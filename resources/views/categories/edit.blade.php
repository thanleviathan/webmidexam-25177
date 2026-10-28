<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Category</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                @include('categories._form', [
                    'action' => route('categories.update', $category),
                    'method' => 'PUT',
                    'category' => $category,
                ])
            </div>
        </div>
    </div>
</x-app-layout>
