<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = trim($this->input('login_id'));
        $password = $this->input('password');
        $remember = $this->boolean('remember');

        // 1. Standard attempt by login_id
        if (Auth::attempt(['login_id' => $loginInput, 'password' => $password], $remember)) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // 2. Standard attempt by email
        if (Auth::attempt(['email' => $loginInput, 'password' => $password], $remember)) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // 3. Case-insensitive lookup by login_id or email
        $user = User::whereRaw('LOWER(login_id) = ?', [strtolower($loginInput)])
            ->orWhereRaw('LOWER(email) = ?', [strtolower($loginInput)])
            ->first();

        if ($user && Auth::attempt(['id' => $user->id, 'password' => $password], $remember)) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login_id' => 'Invalid Login ID / Email or Password. Please verify and try again.',
        ]);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login_id' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login_id')).'|'.$this->ip());
    }
}
