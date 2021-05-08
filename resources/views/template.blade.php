<!DOCTYPE html>
<html lang="ja">
<head>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    @yield('head')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">名も無きタイピングゲーム</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ranking') }}">ランキング</a>
                    </li>
                    @if(Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{ route('home') }}" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        マイページ({{Auth::user()->name}})
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="<?= route('users', ['id' => Auth::id()]) ?>">レコード</a>
                        <a class="dropdown-item disabled" href="{{ route('home') }}">設定</a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="post" name="logout_form" id="logout_id">
                            @csrf
                            <a class="dropdown-item" href="javascript:logout_form.submit()">ログアウト</a>
                        </form>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="{{ route('login') }}">ログイン</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="{{ route('register') }}">新規登録</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">マイページ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">新規登録</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('help') }}">ヘルプ</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    @yield('content')
    <footer id="footer" class="modal-footer" style="background-color:rgb(0,84,56); color:white; transform:translateY(1px); border-bottom-right-radius:0; border-bottom-left-radius:0; margin-top:16px;">
        <p style="margin:0 auto;">Copyright © 2021 俺</p>
    </footer>
    <script>
        new function(){
            var footerId = "footer";
            //メイン
            function footerFixed(){
                //ドキュメントの高さ
                var dh = document.getElementsByTagName("body")[0].clientHeight;
                //フッターのtopからの位置
                document.getElementById(footerId).style.top = "0px";
                var ft = document.getElementById(footerId).offsetTop;
                //フッターの高さ
                var fh = document.getElementById(footerId).offsetHeight;
                //ウィンドウの高さ
                if (window.innerHeight){
                    var wh = window.innerHeight;
                }else if(document.documentElement && document.documentElement.clientHeight != 0){
                    var wh = document.documentElement.clientHeight;
                }
                if(ft+fh<wh){
                    document.getElementById(footerId).style.position = "relative";
                    document.getElementById(footerId).style.top = (wh-fh-ft-1)+"px";
                }
            }
            
            //文字サイズ
            function checkFontSize(func){
            
                //判定要素の追加	
                var e = document.createElement("div");
                var s = document.createTextNode("S");
                e.appendChild(s);
                e.style.visibility="hidden"
                e.style.position="absolute"
                e.style.top="0"
                document.body.appendChild(e);
                var defHeight = e.offsetHeight;
                
                //判定関数
                function checkBoxSize(){
                    if(defHeight != e.offsetHeight){
                        func();
                        defHeight= e.offsetHeight;
                    }
                }
                setInterval(checkBoxSize,1000)
            }
            
            //イベントリスナー
            function addEvent(elm,listener,fn){
                try{
                    elm.addEventListener(listener,fn,false);
                }catch(e){
                    elm.attachEvent("on"+listener,fn);
                }
            }

            addEvent(window,"load",footerFixed);
            addEvent(window,"load",function(){
                checkFontSize(footerFixed);
            });
            addEvent(window,"resize",footerFixed);
        }
    </script>
</body>
</html>