<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect('/attendance');
            }
        });

        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                if ($request->role == 'admin') {
                    return redirect('/admin/attendance/list');
                } else {
                    return redirect('/attendance');
                }
            }
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            if ($request->role == 'admin') {
                $user = Admin::where('email', $request->email)->first();
                if (!$user) {
                    throw ValidationException::withMessages([
                        'email' => ['管理者としてのログイン情報が登録されていません。'],
                    ]);
                }
                if (!Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'password' => ['パスワードが正しくありません'],
                    ]);
                }
                Auth::guard('admin')->login($user);
                return $user;
            } else {
                $user = User::where('email', $request->email)->first();
                if (!$user) {
                    throw ValidationException::withMessages([
                        'email' => ['ログイン情報が登録されていません。'],
                    ]);
                }
                if (!Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'password' => ['パスワードが正しくありません'],
                    ]);
                }
                Auth::guard('web')->login($user);
                return $user;
            }
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by($request->input('email') . $request->ip());
        });
    }
}
