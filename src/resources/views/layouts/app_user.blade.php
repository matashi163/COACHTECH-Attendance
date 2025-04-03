@extends('layouts.base')

@section('css')
<link rel="stylesheet" href="{{asset('css/app.css')}}">
@yield('app_css')
@endsection

@section('header')
<div class="header__content">
    <div class="header__buttons">
        <a href="/attendance" class="header__button">勤怠</a>
        <a href="/attendance/list" class="header__button">勤怠一覧</a>
        <a href="/stamp_correction_request/list" class="header__button">申請</a>
        <form action="/logout" method="post">
            @csrf
            <button class="header__button">ログアウト</button>
        </form>
    </div>
</div>
@endsection