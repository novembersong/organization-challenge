<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'pic_name' => ['required', 'string', 'max:255'],
            'email_address' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'id_role' => ['required'],
            'id_organization' => ['required'],
            'photo' => ['nullable'],
            'pic_phone' => ['required'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();


        return User::create([
            'name' => $input['pic_name'],
            'id_role' => $input['id_role'],
            'id_organization' => $input['id_organization'],
            'phone' => $input['pic_phone'],
            'email' => $input['email_address'],
            'password' => Hash::make($input['password']),
            'profile_photo_path' => $input['photo']
        ]);
    }
}
