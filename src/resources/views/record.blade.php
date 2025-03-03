@extends('layouts.app_user')

@section('app_css')
<link rel="stylesheet" href="{{asset('css/record.css')}}">
@endsection

@section('content')
<div class="record__content">
    <p class="status">{{$status->status}}</p>
    <p class="date"><span id="date"></span></p>
    <p class="time"><span id="time"></span></p>
    <div class="buttons">
        @if($status->id === 1)
        <a href="" class="button button--black">出勤</a>
        @elseif($status->id === 2)
        <a href="" class="button button--black">退勤</a>
        <a href="" class="button button--white">休憩入</a>
        @elseif($status->id === 3)
        <a href="" class="button button--white">休憩戻</a>
        @elseif($status->id === 4)
        <p class="button--text">お疲れ様でした。</p>
        @endif
    </div>
</div>

<script>
    function updateTime() {
        const now = new Date();

        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const weekdays = ["日", "月", "火", "水", "木", "金", "土"];
        const weekday = weekdays[now.getDay()];
        const hour = String(now.getHours()).padStart(2, '0');
        const minute = String(now.getMinutes()).padStart(2, '0');

        document.getElementById('date').innerText = `${year}年${month}月${day}日(${weekday})`;
        document.getElementById('time').innerText = `${hour}:${minute}`;
    }

    setInterval(updateTime, 1000);
    window.onload = updateTime;
</script>
@endsection