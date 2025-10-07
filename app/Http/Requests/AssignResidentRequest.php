<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required','integer','exists:users,id'],
            'resident_type' => ['required','in:owner,tenant,family_member,guest'],
            'move_in_date' => ['required','date'],
            'move_out_date' => ['nullable','date','after_or_equal:move_in_date'],
            'rent_amount' => ['nullable','numeric','min:0'],
            'status' => ['required','in:active,inactive'],
            'notes' => ['nullable','string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = (int) $this->input('user_id');
            if ($userId > 0) {
                $alreadyAssigned = \App\Models\FlatResident::query()
                    ->where('user_id', $userId)
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->exists();
                if ($alreadyAssigned) {
                    $validator->errors()->add('user_id', 'Bu kullanıcı zaten bir daireye atanmış.');
                }
            }
        });
    }
}


