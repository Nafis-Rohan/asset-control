<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $stats = [];
        $myAssets = collect();

        if ($user->isAdmin()) {
            $stats = [
                ['label' => 'Total Assets', 'value' => Asset::count(), 'color' => 'blue'],
                ['label' => 'Available Assets', 'value' => Asset::where('status', 'available')->count(), 'color' => 'green'],
                ['label' => 'Pending Requests', 'value' => AssetRequest::where('status', 'pending')->count(), 'color' => 'yellow'],
                ['label' => 'Assigned Assets', 'value' => Asset::where('status', 'assigned')->count(), 'color' => 'indigo'],
            ];
        } elseif ($user->isManager()) {
            $departmentRequestQuery = AssetRequest::whereHas('user', function ($q) use ($user) {
                $q->where('department', $user->department);
            });

            $stats = [
                ['label' => 'Pending Approvals', 'value' => (clone $departmentRequestQuery)->where('status', 'pending')->count(), 'color' => 'yellow'],
                ['label' => 'Department Requests', 'value' => $departmentRequestQuery->count(), 'color' => 'blue'],
                ['label' => 'Approved This Month', 'value' => (clone $departmentRequestQuery)->where('status', 'approved')->whereMonth('updated_at', now()->month)->count(), 'color' => 'green'],
            ];
        } else {
            $stats = [
                ['label' => 'My Assets', 'value' => $user->assets()->count(), 'color' => 'indigo'],
                ['label' => 'My Requests', 'value' => $user->assetRequests()->count(), 'color' => 'blue'],
                ['label' => 'Pending Requests', 'value' => $user->assetRequests()->where('status', 'pending')->count(), 'color' => 'yellow'],
            ];
            $myAssets = $user->assets()->latest()->get();
        }

        return view('dashboard', compact('stats', 'myAssets'));
    }
}
