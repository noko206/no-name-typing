@extends('template')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>ヘルプ</title>
@endsection

@section('content')
    <p class="h5 p-2 m-3 bg-info text-white">遊び方がわかりません</p>
    <p><ol class="mr-4">
        <li>表示されている単語を一つずつタイピングしていきます。（開始ボタン等はないので好きなタイミングで打ち始めてください。打ち始めるとタイマーが稼働します。）</li>
        <li>「＿」（アンダーバー）はスペースキーを入力してください。</li>
        <li>ミスによるスコアの減点はありません。</li>
        <li>400文字タイピングしたら終了です。</li>
    </ol></p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">jaをzyaと入力したらタイプ数はどうなりますか</p>
    <p class="ml-4 mr-4">jaはタイプ数2、zyaはタイプ数3でカウントされます。zyaと入力してもタイプ数が2でカウントされることはないので、実際の入力回数より少ない数でカウントされるようなことはありません。<br>
        ローマ字入力のキーの組み合わせは以下を参照してください。大抵の組み合わせには対応しています。
        <div style="margin:20px">
            <div class="iframely-embed"><div class="iframely-responsive" style="height: 140px; padding-bottom: 0;"><a href="https://support.microsoft.com/ja-jp/topic/%25E3%2583%25AD%25E3%2583%25BC%25E3%2583%259E%25E5%25AD%2597%25E5%2585%25A5%25E5%258A%259B%25E3%2581%25AE%25E3%2581%25A4%25E3%2581%25A5%25E3%2582%258A%25E4%25B8%2580%25E8%25A6%25A7%25E8%25A1%25A8%25E3%2582%2592%25E7%25A2%25BA%25E8%25AA%258D%25E3%2581%2597%25E3%2581%25A6%25E3%2581%25BF%25E3%2582%2588%25E3%2581%2586-bcc0ad7e-2781-cc9a-e524-7de506d8fdae" data-iframely-url="//cdn.iframe.ly/8ICYaEL"></a></div></div><script async src="//cdn.iframe.ly/embed.js" charset="utf-8"></script>
        </div>
    </p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">「＿」（アンダーバー）の直前の「ん」の入力はnとnnどちらですか</p>
    <p class="ml-4 mr-4">どちらでも問題ありません。どちらで入力してもミスになりません。また、nはタイプ数1、nnはタイプ数2できちんとカウントされます。（もちろんxnでも問題ありません。）</p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">タイマーの精度が悪くないですか</p>
    <p class="ml-4 mr-4">見た目上そう見えるだけです。実際はもっと精度が良いです。（負荷を軽減するために描画回数を減らしています。）</p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">ランキングに登録できません</p>
    <p class="ml-4 mr-4">ログインしてください。ログインしているとトップ画面の右上部にメッセージが表示されます。</p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">ランクって何ですか</p>
    <p class="ml-4 mr-4">タイムに基づくタイピング速度の指標です。<br>
        230秒を切るとランクF。
        ランクFからランクAまでは10秒短縮するごとにランクが1つずつ上がっていきます。<br>
        （230秒=>F, 220秒=>F+, 210秒=>E-, ... , 80秒=>A）<br>
        ランクAからは2秒短縮するごとにランクが1つずつ上がっていきます。<br>
        （78秒=>A+, 76秒=>S, 74秒=>SS, 72秒=>SSS, ...）</p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">マイページのレコードに記録が反映されません</p>
    <p class="ml-4 mr-4">ランキングに登録しないと記録は保存されません。</p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">Top15とTop99って何ですか</p>
    <p class="ml-4 mr-4">上位の記録の平均になります。Top15が上位15個のデータの平均、Top99が上位99個のデータの平均です。<br>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">マイページのレコードの記録が99個までしか表示されません</p>
    <p class="ml-4 mr-4">仕様です。</p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">IEで開くと正常に動作しません。</p>
    <p class="ml-4 mr-4">すみません。Chrome、Edge、Firefoxでは正常に動作することを確認しているので、そちらで開くようにしてください。</p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">おすすめのタイピングゲームはありますか</p>
    <p class="ml-4 mr-4">
        よく遊んでいる（遊んでいた）タイピングゲームを列挙してみました。順番は思いついた順です。
        <ul class="ml-2">
            <li><a href="http://tanon710.s500.xrea.com/typewell_mirror/download.html">タイプウェル</a></li>
            ――多機能なタイピングソフト。詳細なリザルトから様々な分析が可能。
            <li><a href="https://www.e-typing.ne.jp/member/">e-typing</a></li>
            ――ブラウザ上でできるタイピングゲーム。常にランキングが賑わっているのが特徴。
            <li><a href="https://typing.tanonews.com/">タイピング速度測定</a></li>
            ――こちらもブラウザ上でできるタイピングゲーム。長文が打ちたい人はこれ。
            <li><a href="https://denasu.com/software/weathertyping.html">Weather Typing</a></li>
            ――対戦型タイピングソフト。一応コンピュータとも対戦できる。
            <li><a href="https://typing-tube.net/">Typing Tube</a></li>
            ――Youtubeの音楽に合わせてタイピングするゲーム。有志のユーザが譜面を作成している。ログインすると使える機能が増える。
            <li><a href="https://casual.hange.jp/typing/">歌謡タイピング劇場</a></li>
            ――こちらも音楽に合わせてタイピングするゲーム。最大4人で協力プレイができる。
            <li><a href="http://typingx0.net/sushida/">寿司打</a></li>
            ――王道のタイピングゲーム。スコアが反射神経と正確性にほぼ依存する。
            <li><a href="https://typing.twi1.me/">myTyping</a></li>
            ――ユーザが作成したお題をタイピングできるサイト。定期的に大会を開催している。
            <li><a href="https://www.freem.ne.jp/dl/win/9924">散歩するキーボード使い</a></li>
            ――謎解きタイピングゲーム。RPGの要素も兼ねている。
            <li><a href="http://typetoon.nonip.net/">TypeToon</a></li>
            ――陣取りゲーム×タイピング。ワードはすべて英語。
            <li><a href="http://hagane-forge.sakura.ne.jp/software/typelighter/">TypeLighter</a></li>
            ――タイプウェルとe-typingの特定のワードや文章を繰り返し練習できるソフト。ストイックな人向け。
            <li><a href="https://github.com/jz5/namatyping">ニコ生タイピング</a></li>
            ――ニコ生のコメントを利用したタイピングゲーム。曲に合わせて歌詞を変換ありでタイピングする。一応ローカルでもプレイ可能。
            <li><a href="http://sintyaku.pa.land.to/daken2/tajisakumagosou.htm">たじさくそうまごウェル</a></li>
            ――「たじたじ」「さくさく」「そうそうたる」「まごまご」しかワードがないタイプウェル。自分の限界速度を引き上げたい人向け。
            <li><a href="http://www.sightseekerstudio.com/typingmania/download.html">TYPINGMANIA</a></li>
            ――曲に合わせて歌詞をタイピングするゲーム。基本的にはローカルのブラウザでプレイすることになる。譜面の自作がほぼ必須。
        </ul>
    </p>
    <p class="h5 m-3 p-2 mb-2 bg-info text-white">タイピングが速い人ってどのくらい速いの</p>
    <p class="ml-4 mr-4">平均で秒間18～20打鍵くらいです。――といってもにわかには信じがたい数字です。本当にそんなスピードでタイピングができるのかと疑問を持たれるかとは思いますが、とはいえ論より証拠。以下に、数多のタイピングガチ勢が集う大会REALFORCE TYPING CHAMPIONSHIP（RTC）についての記事とアーカイブを載せておきました。興味のある方は是非一度目を通していただけたら嬉しいです。<br>
        <div style="margin:20px">
            <div class="iframely-embed"><div class="iframely-responsive" style="height: 140px; padding-bottom: 0;"><a href="https://www.famitsu.com/news/201709/29143039.html" data-iframely-url="//cdn.iframe.ly/pgHld7W?iframe=card-small"></a></div></div><script async src="//cdn.iframe.ly/embed.js" charset="utf-8"></script>
            <br>
            <div class="iframely-embed"><div class="iframely-responsive" style="height: 140px; padding-bottom: 0;"><a href="https://www.famitsu.com/news/201912/22189588.html" data-iframely-url="//cdn.iframe.ly/X7qtTbV?iframe=card-small"></a></div></div><script async src="//cdn.iframe.ly/embed.js" charset="utf-8"></script>
        </div>
        <div style="text-align:center">
            <iframe style="text-align:center" width="560" height="315" src="https://www.youtube.com/embed/rPpIlV-tBEA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/GEhx_ZGB5Hc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/4usMMWbadks" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </p>
@endsection