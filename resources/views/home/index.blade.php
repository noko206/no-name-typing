@extends('template')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>マイページ</title>
@endsection

@section('content')
    <p>ようこそ、{{ Auth::user()->name }}さん</p>
    <hr>
    <h2>ユーザ名の変更</h2>
    <h2>ログアウト</h2>
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <input type="submit" value="ログアウト">
    </form>
@endsection