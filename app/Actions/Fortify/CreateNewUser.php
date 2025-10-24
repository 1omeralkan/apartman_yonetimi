<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50', 'unique:users,phone'],
            'national_id' => ['nullable', 'string', 'max:11', 'unique:users,national_id'],
            'gender' => ['nullable', 'in:male,female'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'national_id' => $input['national_id'] ?? null,
            'gender' => $input['gender'] ?? null,
            'birth_date' => $input['birth_date'] ?? null,
            'address' => $input['address'] ?? null,
            'emergency_contact_name' => $input['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $input['emergency_contact_phone'] ?? null,
            'password' => Hash::make($input['password']),
            'is_active' => true,
            'is_approved' => false, // Yeni kullanıcılar onay bekler
            'notification_email' => true,
            'notification_sms' => false,
        ]);

        // Varsayılan rol: resident
        $role = Role::firstOrCreate(['name' => 'resident', 'guard_name' => 'web']);
        $user->assignRole($role);

        return $user;
    }
}
