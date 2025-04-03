@extends('layouts.app_' . $auth)

@section('app_css')
<link rel="stylesheet" href="{{asset('css/correction_list.css')}}">
@endsection

@section('content')
<h1 class="title">申請一覧</h1>
<div class="correction-list__content">
    <div class="tag">
        <a href="/stamp_correction_request/list?page=approving" class="tag__link {{$page ? '' : 'tag__link--active'}}">承認待ち</a>
        <a href="/stamp_correction_request/list?page=approved" class="tag__link {{$page ? 'tag__link--active' : ''}}">承認済み</a>
    </div>
    <table class="table">
        <tr class="table__row">
            <th class="table__header table__item--status">状態</th>
            <th class="table__header">名前</th>
            <th class="table__header table__item--date">対象日時</th>
            <th class="table__header table__item--reason">申請理由</th>
            <th class="table__header table__item--date">申請日時</th>
            <th class="table__header">詳細</th>
        </tr>
        @foreach ($correctDatas as $correctData)
        <tr class="table__row">
            <td class="table__item table__item--status">{{$correctData['status'] ? '承認済み' : '承認待ち'}}</td>
            <td class="table__item">{{$correctData['name'] ?? ''}}</td>
            <td class="table__item table__item--date">{{$correctData['date'] ?? ''}}</td>
            <td class="table__item table__item--reason">{{$correctData['reason'] ?? ''}}</td>
            <td class="table__item table__item--date">{{$correctData['correct_date'] ?? ''}}</td>
            <td class="table__item">
                @if (isset($correctData['detail_url']))
                <a href="{{$correctData['detail_url']}}" class="table__detail">詳細</a>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection