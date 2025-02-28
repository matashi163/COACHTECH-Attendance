<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FortifyLoginRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (!User::where('email', $value)->exists()) {
                        $fail('ログイン情報が登録されていません');
                    }
                },
            ],
            'password' => [
                'required',
                'min:8',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $this->email)->first();
                    if ($user && !Hash::check($value, $user->password)) {
                        $fail('パスワードが正しくありません');
                    }
                },
            ],
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メール形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
        ];
    }
}
