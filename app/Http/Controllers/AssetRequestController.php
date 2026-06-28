<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequestForm;
use App\Http\Requests\UpdateAssetRequestStatusRequest;
use App\Models\AssetRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetRequestController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = AssetRequest::query()->with(['user', 'manager']);

        if ($user->isEmployee()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isManager()) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('department', $user->department);
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        return view('requests.index', compact('requests'));
    }

    public function store(StoreAssetRequestForm $request): RedirectResponse
    {
        $request->user()->assetRequests()->create([
            'requested_item' => $request->validated('requested_item'),
            'reason' => $request->validated('reason'),
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Your asset request has been submitted.');
    }

    public function updateStatus(UpdateAssetRequestStatusRequest $request, AssetRequest $assetRequest): RedirectResponse
    {
        $assetRequest->update([
            'status' => $request->validated('status'),
            'manager_id' => $request->user()->id,
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Request status updated successfully.');
    }
}
