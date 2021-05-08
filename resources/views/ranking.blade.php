@extends('template')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>ランキング</title>
@endsection

@section('content')
    <p style="text-align:center; font-size:18px; margin-top:16px;">全国ランキング</p>
    <table class="table table-striped" style="text-align:center; margin-bottom:150px;">
        <thead>
            <tr>
                <th scope="col">順位</th>
                <th scope="col">ユーザ</th>
                <th scope="col">ランク</th>
                <th scope="col">タイム(秒)</th>
                <th scope="col">kps(打/秒)</th>
                <th scope="col">kpm(打/分)</th>
                <th scope="col">ミス</th>
                <th scope="col">正誤率(%)</th>
                <th scope="col">日付</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i < count($results); $i++)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td><a href="users/{{ $results[$i]['user_id'] }}">{{ $users[$results[$i]['user_id']]['name'] }}</a></td>
                    @if($results[$i]['time'] <= 80)
                        <td>{{ $rank_top[(int)($results[$i]['time'] / 2)] }}</td>
                    @elseif($results[$i]['time'] <= 240)
                        <td>{{ $rank_bottom[(int)(($results[$i]['time'] - 80) / 10)] }}</td>
                    @else
                        <td>F-</td>
                    @endif
                    <td>{{ sprintf('%.3f', $results[$i]['time']) }}</td>
                    <td>{{ sprintf('%.2f', 400 / $results[$i]['time'])}}</td>
                    <td>{{ floor(60 * 400 / $results[$i]['time']) }}</td>
                    <td>{{ $results[$i]['miss'] }}</td>
                    <td>{{ sprintf('%.2f', 100 * 400 / (400 + $results[$i]['miss'])) }}</td>
                    <td>{{ $results[$i]['created_at'] }}</td>
                </tr>
            @endfor
        </tbody>
    </table>
@endsection