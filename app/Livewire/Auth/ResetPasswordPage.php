<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Reset Password - Ecommerce')]
class ResetPasswordPage extends Component
{
    public string $token = '';

    #[Url]
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    public function save()
    {
        $this->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset([
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token,
        ], function (User $user, string $password) {
            $password = $this->password;
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')
            : session()->flash('error', 'Something went wrong.');
    }

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
