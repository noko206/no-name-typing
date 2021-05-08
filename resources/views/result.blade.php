@extends('template')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/result.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>リザルト</title>
@endsection

@section('content')
    <div class="content">
        <p class="num">全国ランキング</p>
        <p><span class="junni">{{ $user_junni }}位</span><span class="num"> / {{ $user_num }}人中</span></p>
        <p class="num">個人ランキング</p>
        <p><span class="junni">{{ $my_junni }}位</span><span class="num"> / {{ $my_num }}個中</span></p>
        <button type="submit" onclick="location.href='/'" id="back_button" class="btn btn-outline-dark">タイピングに戻る(Enter)</button>
    </div>
    <script>
        // キー押下イベント
        document.body.addEventListener('keydown', event => {
            if(event.keyCode === 13){
                back_button.click()
            }
        });
        let back_button = document.querySelector('#back_button');
    </script>
@endsection