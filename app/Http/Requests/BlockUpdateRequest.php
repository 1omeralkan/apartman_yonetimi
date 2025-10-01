<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockUpdateRequest extends FormRequest
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
        /** @var \App\Models\Block $block */
        $block = $this->route('block');
        $blockId = $block?->id;
        $siteId = $this->input('site_id', $block?->site_id);

        return [
            'site_id' => ['required', 'integer', 'exists:sites,id'],
            'name' => ['required', 'string', 'max:255', 'unique:blocks,name,' . $blockId . ',id,site_id,' . $siteId],
            'status' => ['required', 'in:active,inactive,maintenance'],
            'total_apartments' => ['required', 'integer', 'min:0'],
            'total_floors' => ['required', 'integer', 'min:0'],
            'flats_per_floor' => ['required', 'integer', 'min:0'],
        ];
    }
}
