@extends('template')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
@endsection

@section('content')
    <main class="form-signin">
        <form action="{{ route('register') }}" method="post">
            @csrf
            <h1 class="h3 mb-3 fw-normal">新規登録</h1><br>
            @include('commons/flash')
            <label for="inputName" class="visually-hidden">名前</label>
            <input type="text" name="name" value="{{ old('name') }}" id="inputName" class="form-control" placeholder="名前" required autofocus>
            <label for="inputEmail" class="visually-hidden">メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}" id="inputEmail" class="form-control" placeholder="メールアドレス" required>
            <label for="inputPassword" class="visually-hidden">パスワード</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="パスワード" required>
            <label for="inputPasswordConfirmation" class="visually-hidden">パスワード確認</label>
            <input type="password" name="password_confirmation" id="inputPasswordConfirmation" class="form-control" placeholder="パスワード確認" required><br>
            <button class="w-100 btn btn-lg btn-primary" type="submit">新規登録</button>
        </form>
    </main>
@endsection