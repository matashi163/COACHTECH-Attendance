@extends('layouts.base')

@section('css')
<link rel="stylesheet" href="{{asset('css/app.css')}}">
@yield('app_css')
@endsection

@section('header')
<div class="header__content">
    <div class="header__buttons">
        <a href="/admin/attendance/list" class="header__button">勤怠一覧</a>
        <a href="/admin/staff/list" class="header__button">スタッフ一覧</a>
        <a href="/stamp_correction_request/list" class="header__button">申請一覧</a>
        <form action="/logout" method="post">
            @csrf
            <button class="header__auth header__button">ログアウト</button>
        </form>
    </div>
</div>
@endsection