<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApartmentUpdateRequest extends FormRequest
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
        /** @var \App\Models\Apartment $apartment */
        $apartment = $this->route('apartment');
        $apartmentId = is_object($apartment) ? $apartment->id : $apartment;
        $blockId = $this->input('block_id', $apartment?->block_id);

        return [
            'site_id' => ['required','integer','exists:sites,id'],
            'block_id' => ['required','integer','exists:blocks,id'],
            'name' => ['required','string','max:255','unique:apartments,name,'.$apartmentId.',id,block_id,'.$blockId],
            'address' => ['required','string'],
            'status' => ['required','in:active,inactive,maintenance'],
            'total_floors' => ['required','integer','min:0'],
            'total_flats' => ['required','integer','min:0'],
            'flats_per_floor' => ['required','integer','min:0'],
            'has_elevator' => ['required','boolean'],
            'has_parking' => ['required','boolean'],
        ];
    }
}
