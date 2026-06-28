<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <p class="text-gray-600">
                    Welcome back, <span class="font-semibold text-gray-900">{{ Auth::user()->name }}</span>.
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 capitalize ms-1">
                        {{ Auth::user()->role }}
                    </span>
                    @if (Auth::user()->department)
                        <span class="text-gray-500">· {{ Auth::user()->department }}</span>
                    @endif
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($stats as $stat)
                    @php
                        $colors = [
                            'blue' => 'bg-blue-50 border-blue-200 text-blue-700',
                            'green' => 'bg-green-50 border-green-200 text-green-700',
                            'yellow' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
                            'indigo' => 'bg-indigo-50 border-indigo-200 text-indigo-700',
                        ];
                        $color = $colors[$stat['color']] ?? $colors['blue'];
                    @endphp
                    <div class="rounded-lg border p-6 shadow-sm {{ $color }}">
                        <p class="text-sm font-medium opacity-80">{{ $stat['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('assets.index') }}" class="block rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:border-indigo-300 hover:shadow-md transition">
                        <h3 class="font-semibold text-gray-900">Manage Inventory</h3>
                        <p class="mt-1 text-sm text-gray-600">View and manage all company assets.</p>
                    </a>
                @endif

                <a href="{{ route('requests.index') }}" class="block rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:border-indigo-300 hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-900">
                        @if (Auth::user()->isManager())
                            Review Requests
                        @elseif (Auth::user()->isEmployee())
                            My Requests
                        @else
                            All Requests
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        @if (Auth::user()->isManager())
                            Approve or deny department asset requests.
                        @elseif (Auth::user()->isEmployee())
                            Submit and track your equipment requests.
                        @else
                            Monitor all asset requests across the organization.
                        @endif
                    </p>
                </a>
            </div>

            @if (Auth::user()->isEmployee() && isset($myAssets) && $myAssets->isNotEmpty())
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">My Assigned Assets</h3>
                    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Serial #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($myAssets as $asset)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $asset->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $asset->serial_number }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $asset->category }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @include('partials.status-badge', ['status' => $asset->status, 'type' => 'asset'])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
