@extends('layouts.app_' . $auth)

@section('app_css')
<link rel="stylesheet" href="{{asset('css/attendance_list.css')}}">
@endsection

@section('content')
<h1 class="title">{{$title}}</h1>
<div class="attendance-list__content">
    <div class="select-list">
        <a href="{{$url . '?month=' . $month->copy()->subMonth()->format('Y-m')}}" class="select-list__transition">
            <img src="{{Storage::url('view_images/arrow.png')}}" alt="←" class="select-list__transition--image">
            <p class="select-list__transition--text">前月</p>
        </a>
        <div class="select-list__current">
            <img src="{{Storage::url('view_images/calendar.png')}}" alt="📅" class="select-list__current--image">
            <p class="select-list__current--text">{{$month->locale('ja')->isoFormat('YYYY/MM')}}</p>
        </div>
        <a href="{{$url . '?month=' . $month->copy()->addMonth()->format('Y-m')}}" class="select-list__transition">
            <p class="select-list__transition--text">翌月</p>
            <img src="{{Storage::url('view_images/arrow.png')}}" alt="←" class="select-list__transition--image arrow-reverse">
        </a>
    </div>
    <table class="table">
        <tr class="table__row">
            <th class="table__header">日付</th>
            <th class="table__header">出勤</th>
            <th class="table__header">退勤</th>
            <th class="table__header">休憩</th>
            <th class="table__header">合計</th>
            <th class="table__header">詳細</th>
        </tr>
        @foreach ($attendanceDatas as $attendanceData)
        <tr class="table__row">
            <td class="table__item">{{$attendanceData['date'] ?? ''}}</td>
            <td class="table__item">{{$attendanceData['work_start'] ?? ''}}</td>
            <td class="table__item">{{$attendanceData['work_finish'] ?? ''}}</td>
            <td class="table__item">{{$attendanceData['break_time'] ?? ''}}</td>
            <td class="table__item">{{$attendanceData['work_time'] ?? ''}}</td>
            <td class="table__item">
                @if (isset($attendanceData['detail_url']))
                <a href="{{$attendanceData['detail_url']}}" class="table__detail">詳細</a>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection