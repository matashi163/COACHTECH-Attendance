@extends('layouts.base')

@section('css')
<link rel="stylesheet" href="{{asset('css/auth.css')}}">
@endsection

@section('content')
<div class="auth__content">
    <h1 class="auth__title">ログイン</h1>
    <form action="/login" method="post" class="auth__form">
        @csrf
        <div class="form__content">
            <div class="form__group">
                <p class="form__label">メールアドレス</p>
                <input type="text" name="email" value="{{old('email')}}" class="form__input">
                <p class="form__error">
                    @error('email')
                    {{$errors->first('email')}}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <p class="form__label">パスワード</p>
                <input type="password" name="password" class="form__input">
                <p class="form__error">
                    @error('password')
                    {{$errors->first('password')}}
                    @enderror
                </p>
            </div>
        </div>
        <button class="form__button">ログインする</button>
    </form>
    <a href="/register" class="auth__transition">会員登録はこちら</a>
</div>
@endsection