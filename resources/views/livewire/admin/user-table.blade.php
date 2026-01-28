<div class="p-6 bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">User Management</h2>
        <button wire:click="create"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Add New User
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div class="relative w-full sm:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by username..."
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all">

            @if($search)
                <button wire:click="$set('search', '')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    title="Clear search">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>

        <div class="flex items-center space-x-2 text-sm text-gray-500">
            @if($search)
                <span>Showing results for "<span class="font-medium text-gray-900">{{ $search }}</span>"</span>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        @if($users->count())
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Name
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm space-x-2">
                                <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $user->id }})" class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @else
            <div class="py-12 text-center text-gray-500">
                No users found.
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">
                        {{ $selected_id ? 'Edit User' : 'Add New User' }}
                    </h3>
                    <button wire:click="$set('isOpen', false)" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="px-6 py-4 space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model.defer="name"
                                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Password
                                @if($selected_id)
                                    <span class="text-xs text-gray-400">(leave blank to keep)</span>
                                @endif
                            </label>
                            <input type="password" wire:model.defer="password"
                                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('password') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                        <button type="button" wire:click="$set('isOpen', false)"
                            class="px-4 py-2 text-sm border rounded-md">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            {{ $selected_id ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>