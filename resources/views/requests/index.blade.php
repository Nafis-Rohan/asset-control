<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if (Auth::user()->isEmployee())
                {{ __('My Asset Requests') }}
            @elseif (Auth::user()->isManager())
                {{ __('Department Requests') }}
            @else
                {{ __('All Asset Requests') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (Auth::user()->isEmployee())
                <div class="mb-6 rounded-lg bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Submit New Request</h3>
                    <form method="POST" action="{{ route('requests.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="requested_item" :value="__('Requested Item')" />
                            <x-text-input id="requested_item" name="requested_item" type="text" class="mt-1 block w-full" :value="old('requested_item')" placeholder="e.g. MacBook Pro 16" required />
                            <x-input-error :messages="$errors->get('requested_item')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="reason" :value="__('Reason')" />
                            <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('reason') }}</textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>
                        <x-primary-button>{{ __('Submit Request') }}</x-primary-button>
                    </form>
                </div>
            @endif

            <div class="mb-4">
                <form method="GET" action="{{ route('requests.index') }}" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Filter by status</label>
                        <select name="status" id="status" class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All</option>
                            @foreach (['pending', 'approved', 'denied', 'fulfilled'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Filter</button>
                </form>
            </div>

            <div class="space-y-4">
                @forelse ($requests as $request)
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $request->requested_item }}</h3>
                                    @include('partials.status-badge', ['status' => $request->status, 'type' => 'request'])
                                </div>
                                <p class="mt-2 text-sm text-gray-600">{{ $request->reason }}</p>
                                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                                    <span>Requested by: <strong class="text-gray-700">{{ $request->user->name }}</strong></span>
                                    @if ($request->user->department)
                                        <span>Department: {{ $request->user->department }}</span>
                                    @endif
                                    <span>{{ $request->created_at->format('M d, Y') }}</span>
                                    @if ($request->manager)
                                        <span>Reviewed by: {{ $request->manager->name }}</span>
                                    @endif
                                </div>
                            </div>

                            @if ($request->status === 'pending' && (Auth::user()->isManager() || Auth::user()->isAdmin()))
                                @if (Auth::user()->isAdmin() || $request->user->department === Auth::user()->department)
                                    <div class="flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('requests.update-status', $request) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('requests.update-status', $request) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="denied">
                                            <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                                                Deny
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endif

                            @if ($request->status === 'approved' && Auth::user()->isAdmin())
                                <form method="POST" action="{{ route('requests.update-status', $request) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="fulfilled">
                                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                        Mark Fulfilled
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg bg-white p-8 text-center text-gray-500 shadow-sm">
                        No requests found.
                    </div>
                @endforelse
            </div>

            @if ($requests->hasPages())
                <div class="mt-6">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
