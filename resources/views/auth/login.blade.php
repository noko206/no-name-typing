@extends('template')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
@endsection

@section('content')
    <main class="form-signin">
        <form action="{{ route('login') }}" method="post">
            @csrf
            <h1 class="h3 mb-3 fw-normal">ログイン</h1><br>
            @include('commons/flash')
            <label for="inputEmail" class="visually-hidden">メールアドレス</label>
            <input type="email" name="email" id="inputEmail" value="{{ old('email') }}" class="form-control" placeholder="メールアドレス" required autofocus>
            <label for="inputPassword" class="visually-hidden">パスワード</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="パスワード" required><br>
            <button class="w-100 btn btn-lg btn-primary" type="submit">ログイン</button>
        </form>
        <br>
        <a href="{{ route('register') }}">新規登録</a>
    </main>
@endsection