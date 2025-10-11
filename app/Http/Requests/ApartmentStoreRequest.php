<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApartmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $blockId = $this->input('block_id');

        return [
            'site_id' => ['required','integer','exists:sites,id'],
            'block_id' => ['required','integer','exists:blocks,id'],
            'name' => ['required','string','max:255','unique:apartments,name,NULL,id,block_id,'.$blockId],
            'address' => ['required','string'],
            'status' => ['required','in:active,inactive,maintenance'],
            // total_floors türetilecek
            'total_flats' => ['nullable','integer','min:0'],
            'flats_per_floor' => ['required','integer','min:0'],
            'has_elevator' => ['required','boolean'],
            'has_parking' => ['required','boolean'],
        ];
    }
}
