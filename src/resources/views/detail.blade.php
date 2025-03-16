@extends('layouts.app_' . $auth)

@section('app_css')
<link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection

@section('content')
<h1 class="title">勤怠詳細</h1>
<div class="detail__content">
    <form action="" method="post" class="detail__form">
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
                    <input type="time" name="work_start" value="{{$attendanceData['work_start']->format('H:i')}}" class="form__input--time">
                    <p class="form__input--tilde">～</p>
                    <input type="time" name="work_finish" value="{{$attendanceData['work_finish']->format('H:i')}}" class="form__input--time">
                </div>
            </div>
            @foreach ($attendanceData['break_times'] as $count => $breakTime)
            <div class="form__group">
                <p class="form__label">休憩{{$count + 1}}</p>
                <div class="form__input">
                    <input type="time" name="break_start_{{$count + 1}}" value="{{$breakTime['break_start']->format('H:i')}}" class="form__input--time">
                    <p class="form__input--tilde">～</p>
                    <input type="time" name="break_finish_{{$count + 1}}" value="{{$breakTime['break_finish']->format('H:i')}}" class="form__input--time">
                </div>
            </div>
            @endforeach
            <div class="form__group">
                <p class="form__label">休憩{{count($attendanceData['break_times']) + 1}}</p>
                <div class="form__input">
                    <input type="time" name="break_start_{{count($attendanceData['break_times']) + 1}}" class="form__input--time">
                    <p class="form__input--tilde">～</p>
                    <input type="time" name="break_finish_{{count($attendanceData['break_times']) + 1}}" class="form__input--time">
                </div>
            </div>
            <div class="form__group">
                <p class="form__label">備考</p>
                <div class="form__input">
                    <textarea name="notes" class="form__input--notes"></textarea>
                </div>
            </div>
        </div>
        <button class="form__button">修正</button>
    </form>
</div>
@endsection