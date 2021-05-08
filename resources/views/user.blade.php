@extends('template')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>ユーザーレコード</title>
@endsection

@section('content')
    <p style="text-align:center; font-size:18px; margin-top:16px;">{{ $user[0]['name'] }}さんのレコード</p>
    <table class="table table-striped" style="text-align:center; margin-bottom:150px;">
        <thead>
            <tr>
                <th scope="col">#</th>
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
            <tr>
                <th scope="row">Top1</th>
                @if ($play_count >= 1)
                    @if($top1['time'] <= 80)
                        <td>{{ $rank_top[(int)($top1['time'] / 2)] }}</td>
                    @elseif($top1['time'] <= 240)
                        <td>{{ $rank_bottom[(int)(($top1['time'] - 80) / 10)] }}</td>
                    @else
                        <td>F-</td>
                    @endif
                    <td>{{ sprintf('%.3f', $top1['time']) }}</td>
                    <td>{{ sprintf('%.2f', $top1['kps']) }}</td>
                    <td>{{ floor($top1['kpm']) }}</td>
                    <td>{{ $top1['miss'] }}</td>
                    <td>{{ sprintf('%.2f', $top1['accuracy']) }}</td>
                    <td>{{ $top1['created_at'] }}</td>
                @else
                    @for($i=0; $i < 7; $i++)
                        <td></td>
                    @endfor
                @endif
            </tr>
            <tr>
                <th scope="row">Top15</th>
                @if ($play_count >= 15)
                    @if($top15['time'] <= 80)
                        <td>{{ $rank_top[(int)($top15['time'] / 2)] }}</td>
                    @elseif($top15['time'] <= 240)
                        <td>{{ $rank_bottom[(int)(($top15['time'] - 80) / 10)] }}</td>
                    @else
                        <td>F-</td>
                    @endif
                    <td>{{ sprintf('%.3f', $top15['time']) }}</td>
                    <td>{{ sprintf('%.2f', $top15['kps']) }}</td>
                    <td>{{ floor($top15['kpm']) }}</td>
                    <td>{{ round($top15['miss']) }}</td>
                    <td>{{ sprintf('%.2f', $top15['accuracy']) }}</td>
                    <td> - </td>
                @else
                    @for($i=0; $i < 7; $i++)
                        <td></td>
                    @endfor
                @endif
            </tr>
            <tr>
                <th scope="row">top99</th>
                @if ($play_count >= 99)
                    @if($top99['time'] <= 80)
                        <td>{{ $rank_top[(int)($top99['time'] / 2)] }}</td>
                    @elseif($top99['time'] <= 240)
                        <td>{{ $rank_bottom[(int)(($top99['time'] - 80) / 10)] }}</td>
                    @else
                        <td>F-</td>
                    @endif
                    <td>{{ sprintf('%.3f', $top99['time']) }}</td>
                    <td>{{ sprintf('%.2f', $top99['kps']) }}</td>
                    <td>{{ floor($top99['kpm']) }}</td>
                    <td>{{ round($top99['miss']) }}</td>
                    <td>{{ sprintf('%.2f', $top99['accuracy']) }}</td>
                    <td> - </td>
                @else
                    @for($i=0; $i < 7; $i++)
                        <td></td>
                    @endfor
                @endif
            </tr>
            <tr>
                @for($i=0; $i < 8; $i++)
                    <td></td>
                @endfor
            </tr>
            @for($i=0; $i < count($results); $i++)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
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
                @if($i >= 98)
                    @break
                @endif
            @endfor
        </tbody>
    </table>
@endsection