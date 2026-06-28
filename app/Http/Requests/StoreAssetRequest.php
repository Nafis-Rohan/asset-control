<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'serial_number' => ['required', 'string', 'max:255', 'unique:assets,serial_number'],
            'category' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['available', 'assigned', 'maintenance', 'retired'])],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
