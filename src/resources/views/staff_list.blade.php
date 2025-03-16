@extends('layouts.app_admin')

@section('app_css')
<link rel="stylesheet" href="{{asset('css/staff_list.css')}}">
@endsection

@section('content')
<h1 class="title">スタッフ一覧</h1>
<div class="staff-list__content">
    <table class="table">
        <tr class="table__row">
            <th class="table__header">名前</th>
            <th class="table__header">メールアドレス</th>
            <th class="table__header">月次勤怠</th>
        </tr>
        @foreach ($userDatas as $userData)
        <tr class="table__row">
            <td class="table__item">{{$userData['name']}}</td>
            <td class="table__item">{{$userData['email']}}</td>
            <td class="table__item">
                <a href="{{$userData['detail_url']}}" class="table__detail">詳細</a>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection