<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $siteId = $this->route('site');
        $siteId = is_object($siteId) ? $siteId->id : $siteId;

        return [
            'name' => ['required','string','max:255'],
            'address' => ['required','string'],
            'description' => ['nullable','string'],
            'status' => ['required','in:active,inactive,maintenance'],
            'site_code' => ['required','string','max:50','unique:sites,site_code,'.$siteId],
            'total_blocks' => ['required','integer','min:0'],
            'total_apartments' => ['required','integer','min:0'],
            'total_floors' => ['required','integer','min:0'],
            'flats_per_floor' => ['required','integer','min:0'],
        ];
    }
}
