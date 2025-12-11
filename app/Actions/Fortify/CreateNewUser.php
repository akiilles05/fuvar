<?php

namespace App\Actions\Fortify;

use App\Models\Fuvarozo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): Fuvarozo
    {
        Validator::make($input, [
            'nev' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Fuvarozo::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return Fuvarozo::create([
            'nev' => $input['nev'],
            'email' => $input['email'],
            'jelszo' => $input['password'],
            'szerepkor' => 'fuvarozo',
        ]);
    }
}
