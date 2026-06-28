<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequestStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if (! $user->isManager()) {
            return false;
        }

        $assetRequest = $this->route('assetRequest');

        return $assetRequest
            && $assetRequest->user->department === $user->department;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $user = $this->user();
        $allowedStatuses = $user?->isAdmin()
            ? ['approved', 'denied', 'fulfilled']
            : ['approved', 'denied'];

        return [
            'status' => ['required', Rule::in($allowedStatuses)],
        ];
    }
}
