@extends('layouts.app_' . $auth)

@section('app_css')
<link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection

@section('content')
<h1 class="title">勤怠詳細</h1>
<div class="detail__content">
    <form action="{{$url}}" method="post" class="detail__form">
        @csrf
        <div class="form__content">
            <div class="form__group">
                <p class="form__label">名前</p>
                <div class="form__input">
                    <p class="form__input--name">{{$attendanceData['name']}}</p>
                    <input type="hidden" name="name" value="{{$attendanceData['name']}}">
                </div>
            </div>
            <div class="form__group">
                <p class="form__label">日付</p>
                <div class="form__input">
                    <p class="form__input--date">{{$attendanceData['date']->year}}年</p>
                    <p class="form__input--date">{{$attendanceData['date']->month}}月{{$attendanceData['date']->day}}日</p>
                    <input type="hidden" name="date" value="{{$attendanceData['date']}}">
                </div>
            </div>
            <div class="form__group">
                <p class="form__label">出勤・退勤</p>
                <div class="form__input">
                    <input type="time" name="work_start" value="{{$attendanceData['work_start']->format('H:i')}}" class="form__input--time {{$approving ? 'form__input--non-active' : ''}}">
                    <p class="form__input--tilde">～</p>
                    <input type="time" name="work_finish" value="{{$attendanceData['work_finish']->format('H:i')}}" class="form__input--time {{$approving ? 'form__input--non-active' : ''}}">
                </div>
            </div>
            @foreach ($attendanceData['break_times'] as $count => $breakTime)
            <div class="form__group">
                <p class="form__label">休憩{{$count + 1}}</p>
                <div class="form__input">
                    <input type="time" name="break_start[]" value="{{$breakTime['break_start']->format('H:i')}}" class="form__input--time {{$approving ? 'form__input--non-active' : ''}}">
                    <p class="form__input--tilde">～</p>
                    <input type="time" name="break_finish[]" value="{{$breakTime['break_finish']->format('H:i')}}" class="form__input--time {{$approving ? 'form__input--non-active' : ''}}">
                </div>
            </div>
            @endforeach
            <div class="form__group">
                <p class="form__label">休憩{{count($attendanceData['break_times']) + 1}}</p>
                @if (!$approving)
                <div class="form__input">
                    <input type="time" name="break_start[]" class="form__input--time">
                    <p class="form__input--tilde">～</p>
                    <input type="time" name="break_finish[]" class="form__input--time">
                </div>
                @endif
            </div>
            <div class="form__group">
                <p class="form__label">備考</p>
                <div class="form__input">
                    <textarea name="notes" class="form__input--notes {{$approving ? 'form__input--non-active' : ''}}">{{$attendanceData['notes']}}</textarea>
                </div>
            </div>
        </div>
        <p class="form__error">
            @if ($errors->any())
            {{$errors->first()}}
            @endif
        </p>
        @if ($approving)
        @if ($auth == 'user')
        <p class="form__button--text">*承認待ちのため修正はできません。</p>
        @elseif ($auth == 'admin')
        <button class="form__button">承認</button>
        @endif
        @else
        <button class="form__button">修正</button>
        @endif
    </form>
</div>
@endsection