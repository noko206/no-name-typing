@extends('template')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>{{ config('app.name') }}</title>
    <style>
        body {
            background-image: url("{{ asset('img/top_background.png') }}");
        }
        #main-content {
            width: 860px;
            margin: 0 auto;
            padding-bottom: 30px;
        }
    </style>
@endsection

@section('content')
    @if(Auth::check())
        <div class="login-message">
            <p>{{ $login_message1 }}<?= Auth::user()->name ?>さん。<br>{{ $login_message2 }}</p>
        </div>
    @endif
    <div id="main-content" class="card border-secondary mb-3">
        <div id="current-key-field">
            <p id="current-key-roma"><span id="typed"></span><span id="missed"></span><span id="untyped"></span></p>
        </div>
        <div id="sentence-field" class="card border-secondary mb-3"><p id="sentence"></p></div>
        <table id="curernt-data-table">
            <tr>
                <td class="current-data-td"><span id="type-count" class="param-box">タイプ数：0</span></td>
                <td class="current-data-td"><span id="miss-count" class="param-box">ミス数：0</span></td>
                <td class="current-data-td"><span id="current-time" class="param-box">タイム：0.000</span></td>
            </tr>
        </table>
        <table id="result-table">
            <tr>
                <td rowspan="2" class="result-table-td" id="result-table-rank"><span style="font-size:24px">Rank　</span><span id="rank-span"></span></td>
                <td>
                    <form action="{{ route('results.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="time" id="time-hidden" value="">
                        <input type="hidden" name="miss" id="miss-hidden" value="">
                        <input type="hidden" name="user_id" id="user-id-hidden" value="{{ Auth::id() }}">
                        @if (Auth::check())
                            <button type="submit" id="ranking-button" class="btn btn-outline-dark">ランキングに登録する(Enter)</button>
                        @else
                            <button type="submit" id="ranking-button" class="btn btn-outline-dark" disabled>ランキングに登録する</button>
                        @endif
                    </form>
                </td>
            </tr>
            <tr>
                <td><button id="retry-button" class="btn btn-outline-dark">もう一度プレイ(Esc)</button></td>
            </tr>
        </table>
    </div>
    <p style="text-align:center;">遊び方は<a href="{{ route('help') }}">ヘルプ</a>を参照してください。</p>
    <script>
        function getRandomInt(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min) + min);
        }
        // ワード群をランダムに生成
        function createRandomWords(){
            let cnt = -1;
            let _words = [];
            while(cnt < 405){
                var random = getRandomInt(0, words_obj.length - 1);
                _words.push({'word': words_obj[random]['word'], 'yomi': words_obj[random]['yomi'] + ' '});
                cnt += words_obj[random]['key'] + 1;
            }
            return _words;
        }
        // 新しいワード群をセット
        function setSentence(_words){
            for(let i=0; i<_words.length; i++){
                sentence.innerHTML += '<span class="word_span">' + _words[i]['word'] + '＿</span>';
            }
            word_span = document.querySelectorAll('.word_span');
        }
        // 入力されたキーの処理を行う
        function checkInput(key){
            // 完全一致
            for(const chunk of romaChunk[word_count][chunk_count]){
                if(chunk === current_key + key){
                    // 計測開始
                    if(type_count === 0 && miss_count === 0){
                        start_time = performance.now();
                        timer = setInterval('setCurrentTime()', 100);
                    }
                    // 変数の更新
                    typed.innerHTML = '';
                    missed.innerHTML = '';
                    untyped.innerHTML = '';
                    chunk_count++;
                    type_count++;
                    current_key = '';
                    // 400文字打ったとき
                    if(type_count >= 400){
                        end_time = performance.now();
                        clearInterval(timer);
                        type_count.innerHTML = type_count;
                        time = (end_time - start_time) / 1000;
                        current_time.innerHTML = 'タイム：' + time.toFixed(3);
                        is_clear = true;
                        // currentromaの表示を続ける
                        typed.innerHTML = '　';
                        // sentenceのwordをすべてグレーにする
                        for(const w of word_span){
                            w.style.color = 'rgb(224,224,224)';
                        }
                        // カウントの表示
                        _type_count.innerHTML = 'タイプ数：400';
                        _miss_count.innerHTML = 'ミス数：' + miss_count;
                        // resultの表示
                        rank_span.innerHTML = getRank(time.toFixed(3));
                        result_table.classList.add('fadein');
                        result_table.style.visibility = 'visible';
                        // ランキング登録用のhiddenの更新
                        time_hidden.value = time.toFixed(3);
                        miss_hidden.value = miss_count;
                        // 効果音
                        clear_sound.play();
                        return;
                    }
                    // keyがスペースの場合
                    if(key === ' '){
                        word_count++;
                        chunk_count = 0;
                        typed_roma = [];
                        // 描画
                        for(const r of romaChunk[word_count]){
                            untyped.innerHTML += r[0];
                        }
                        word_span[word_count - 1].style.color = 'rgb(224,224,224)';
                    }
                    else{
                        typed_roma.push(chunk);
                        // 描画
                        for(const t of typed_roma){
                            typed.innerHTML += t;
                        }
                        for(let i=chunk_count; i<romaChunk[word_count].length; i++){
                            untyped.innerHTML += romaChunk[word_count][i][0];
                        }
                    }
                    _type_count.innerHTML = 'タイプ数：' + type_count;
                    return;
                }
            }
            // 部分一致
            for(const chunk of romaChunk[word_count][chunk_count]){
                if(chunk.startsWith(current_key + key)){
                    // 計測開始
                    if(type_count === 0 && miss_count === 0){
                        start_time = performance.now();
                        timer = setInterval('setCurrentTime()', 100);
                    }
                    // 変数の更新
                    typed.innerHTML = '';
                    missed.innerHTML = '';
                    untyped.innerHTML = '';
                    type_count++;
                    current_key += key;
                    // 描画
                    for(const t of typed_roma){
                        typed.innerHTML += t;
                    }
                    typed.innerHTML += current_key;
                    for(let i=current_key.length; i<chunk.length; i++){
                        untyped.innerHTML += chunk[i];
                    }
                    for(let i=chunk_count+1; i<romaChunk[word_count].length; i++){
                        untyped.innerHTML += romaChunk[word_count][i][0];
                    }
                    _type_count.innerHTML = 'タイプ数：' + type_count;
                    return;
                }
            }
            // ミスした場合
            // 計測が開始していない場合
            if(type_count === 0 && miss_count === 0){
                return;
            }
            // 変数の更新
            miss_count++;
            if(missed.innerHTML === ''){
                let tmp = untyped.innerHTML;
                missed.innerHTML = tmp[0];
                untyped.innerHTML = tmp.substring(1);
            }
            _miss_count.innerHTML = 'ミス数：' + miss_count;
            // 効果音
            miss_sound.play();
            // ミスが多すぎる場合
            if(miss_count >= 100){
                alert('ミスが多すぎます。');
                init();
            }
        }
        // yomi_to_romaをget
        function getYomiToRoma(){
            let _yomi_to_roma = {};
            _yomi_to_roma['ぁ'] = ['la', 'xa'];
            _yomi_to_roma['ぁ'] = ['la', 'xa'];
            _yomi_to_roma['あ'] = ['a'];
            _yomi_to_roma['ぃ'] = ['li', 'lyi', 'xi', 'xyi'];
            _yomi_to_roma['い'] = ['i', 'yi'];
            _yomi_to_roma['いぇ'] = ['ye'];
            _yomi_to_roma['ぅ'] = ['lu', 'xu'];
            _yomi_to_roma['う'] = ['u', 'wu', 'whu'];
            _yomi_to_roma['うぁ'] = ['wha'];
            _yomi_to_roma['うぃ'] = ['wi', 'whi'];
            _yomi_to_roma['うぇ'] = ['we', 'whe'];
            _yomi_to_roma['うぉ'] = ['who'];
            _yomi_to_roma['ぇ'] = ['le', 'lye', 'xe', 'xye'];
            _yomi_to_roma['え'] = ['e'];
            _yomi_to_roma['ぉ'] = ['lo', 'xo'];
            _yomi_to_roma['お'] = ['o'];
            _yomi_to_roma['か'] = ['ka', 'ca'];
            _yomi_to_roma['が'] = ['ga'];
            _yomi_to_roma['き'] = ['ki'];
            _yomi_to_roma['きぃ'] = ['kyi'];
            _yomi_to_roma['きぇ'] = ['kye'];
            _yomi_to_roma['きゃ'] = ['kya'];
            _yomi_to_roma['きゅ'] = ['kyu'];
            _yomi_to_roma['きょ'] = ['kyo'];
            _yomi_to_roma['ぎ'] = ['gi'];
            _yomi_to_roma['ぎぃ'] = ['gyi'];
            _yomi_to_roma['ぎぇ'] = ['gye'];
            _yomi_to_roma['ぎゃ'] = ['gya'];
            _yomi_to_roma['ぎゅ'] = ['gyu'];
            _yomi_to_roma['ぎょ'] = ['gyo'];
            _yomi_to_roma['く'] = ['ku', 'cu', 'qu'];
            _yomi_to_roma['くぁ'] = ['kwa', 'qa', 'qwa'];
            _yomi_to_roma['くぃ'] = ['qi', 'qwi', 'qyi'];
            _yomi_to_roma['くぅ'] = ['qwu'];
            _yomi_to_roma['くぇ'] = ['qe', 'qwe', 'qye'];
            _yomi_to_roma['くぉ'] = ['qo', 'qwo'];
            _yomi_to_roma['くゃ'] = ['qya'];
            _yomi_to_roma['くゅ'] = ['qyu'];
            _yomi_to_roma['くょ'] = ['qyo'];
            _yomi_to_roma['ぐ'] = ['gu'];
            _yomi_to_roma['ぐぁ'] = ['gwa'];
            _yomi_to_roma['ぐぃ'] = ['gwi'];
            _yomi_to_roma['ぐぅ'] = ['gwu'];
            _yomi_to_roma['ぐぇ'] = ['gwe'];
            _yomi_to_roma['ぐぉ'] = ['gwo'];
            _yomi_to_roma['け'] = ['ke'];
            _yomi_to_roma['げ'] = ['ge'];
            _yomi_to_roma['こ'] = ['ko', 'co'];
            _yomi_to_roma['ご'] = ['go'];
            _yomi_to_roma['さ'] = ['sa'];
            _yomi_to_roma['ざ'] = ['za'];
            _yomi_to_roma['し'] = ['si', 'shi', 'ci'];
            _yomi_to_roma['しぃ'] = ['syi'];
            _yomi_to_roma['しぇ'] = ['sye', 'she'];
            _yomi_to_roma['しゃ'] = ['sya', 'sha'];
            _yomi_to_roma['しゅ'] = ['syu', 'shu'];
            _yomi_to_roma['しょ'] = ['syo', 'sho'];
            _yomi_to_roma['じ'] = ['ji', 'zi'];
            _yomi_to_roma['じぃ'] = ['jyi', 'zyi'];
            _yomi_to_roma['じぇ'] = ['je', 'jye', 'zye'];
            _yomi_to_roma['じゃ'] = ['ja', 'jya', 'zya'];
            _yomi_to_roma['じゅ'] = ['ju', 'jyu', 'zyu'];
            _yomi_to_roma['じょ'] = ['jo', 'jyo', 'zyo'];
            _yomi_to_roma['す'] = ['su'];
            _yomi_to_roma['すぁ'] = ['swa'];
            _yomi_to_roma['すぃ'] = ['swi'];
            _yomi_to_roma['すぅ'] = ['swu'];
            _yomi_to_roma['すぇ'] = ['swe'];
            _yomi_to_roma['すぉ'] = ['swo'];
            _yomi_to_roma['ず'] = ['zu'];
            _yomi_to_roma['せ'] = ['se', 'ce'];
            _yomi_to_roma['ぜ'] = ['ze'];
            _yomi_to_roma['そ'] = ['so'];
            _yomi_to_roma['ぞ'] = ['zo'];
            _yomi_to_roma['た'] = ['ta'];
            _yomi_to_roma['だ'] = ['da'];
            _yomi_to_roma['ち'] = ['ti', 'chi'];
            _yomi_to_roma['ちぃ'] = ['tyi', 'cyi'];
            _yomi_to_roma['ちぇ'] = ['tye', 'che', 'cye'];
            _yomi_to_roma['ちゃ'] = ['tya', 'cha', 'cya'];
            _yomi_to_roma['ちゅ'] = ['tyu', 'chu', 'cyu'];
            _yomi_to_roma['ちょ'] = ['tyo', 'cho', 'cyo'];
            _yomi_to_roma['ぢ'] = ['di'];
            _yomi_to_roma['ぢぃ'] = ['dyi'];
            _yomi_to_roma['ぢぇ'] = ['dye'];
            _yomi_to_roma['ぢゃ'] = ['dya'];
            _yomi_to_roma['ぢゅ'] = ['dyu'];
            _yomi_to_roma['ぢょ'] = ['dyo'];
            _yomi_to_roma['っ'] = ['ltu', 'ltsu', 'xtsu', 'xtu'];
            _yomi_to_roma['つ'] = ['tu', 'tsu'];
            _yomi_to_roma['つぁ'] = ['tsa'];
            _yomi_to_roma['つぃ'] = ['tsi'];
            _yomi_to_roma['つぇ'] = ['tse'];
            _yomi_to_roma['つぉ'] = ['tso'];
            _yomi_to_roma['づ'] = ['du'];
            _yomi_to_roma['て'] = ['te'];
            _yomi_to_roma['てぃ'] = ['thi'];
            _yomi_to_roma['てぇ'] = ['the'];
            _yomi_to_roma['てゃ'] = ['tha'];
            _yomi_to_roma['てゅ'] = ['thu'];
            _yomi_to_roma['てょ'] = ['tho'];
            _yomi_to_roma['で'] = ['de'];
            _yomi_to_roma['でぃ'] = ['dhi'];
            _yomi_to_roma['でぇ'] = ['dhe'];
            _yomi_to_roma['でゃ'] = ['dha'];
            _yomi_to_roma['でゅ'] = ['dhu'];
            _yomi_to_roma['でょ'] = ['dho'];
            _yomi_to_roma['と'] = ['to'];
            _yomi_to_roma['とぁ'] = ['twa'];
            _yomi_to_roma['とぃ'] = ['twi'];
            _yomi_to_roma['とぅ'] = ['twu', 'toxu'];
            _yomi_to_roma['とぇ'] = ['twe', 'toxe'];
            _yomi_to_roma['とぉ'] = ['two', 'toxo'];
            _yomi_to_roma['ど'] = ['do'];
            _yomi_to_roma['どぁ'] = ['dwa'];
            _yomi_to_roma['どぃ'] = ['dwi'];
            _yomi_to_roma['どぅ'] = ['dwu'];
            _yomi_to_roma['どぇ'] = ['dwe'];
            _yomi_to_roma['どぉ'] = ['dwo'];
            _yomi_to_roma['な'] = ['na'];
            _yomi_to_roma['に'] = ['ni'];
            _yomi_to_roma['にぃ'] = ['nyi'];
            _yomi_to_roma['にぇ'] = ['nye'];
            _yomi_to_roma['にゃ'] = ['nya'];
            _yomi_to_roma['にゅ'] = ['nyu'];
            _yomi_to_roma['にょ'] = ['nyo'];
            _yomi_to_roma['ぬ'] = ['nu'];
            _yomi_to_roma['ね'] = ['ne'];
            _yomi_to_roma['の'] = ['no'];
            _yomi_to_roma['は'] = ['ha'];
            _yomi_to_roma['ば'] = ['ba'];
            _yomi_to_roma['ぱ'] = ['pa'];
            _yomi_to_roma['ひ'] = ['hi'];
            _yomi_to_roma['ひぃ'] = ['hyi'];
            _yomi_to_roma['ひぇ'] = ['hye'];
            _yomi_to_roma['ひゃ'] = ['hya'];
            _yomi_to_roma['ひゅ'] = ['hyu'];
            _yomi_to_roma['ひょ'] = ['hyo'];
            _yomi_to_roma['び'] = ['bi'];
            _yomi_to_roma['びぃ'] = ['byi'];
            _yomi_to_roma['びぇ'] = ['bye'];
            _yomi_to_roma['びゃ'] = ['bya'];
            _yomi_to_roma['びゅ'] = ['byu'];
            _yomi_to_roma['びょ'] = ['byo'];
            _yomi_to_roma['ぴ'] = ['pi'];
            _yomi_to_roma['ぴぃ'] = ['pyi'];
            _yomi_to_roma['ぴぇ'] = ['pye'];
            _yomi_to_roma['ぴゃ'] = ['pya'];
            _yomi_to_roma['ぴゅ'] = ['pyu'];
            _yomi_to_roma['ぴょ'] = ['pyo'];
            _yomi_to_roma['ふ'] = ['fu', 'hu'];
            _yomi_to_roma['ふぁ'] = ['fa', 'fwa'];
            _yomi_to_roma['ふぃ'] = ['fi', 'fwi', 'fyi'];
            _yomi_to_roma['ふぅ'] = ['fwu'];
            _yomi_to_roma['ふぇ'] = ['fe', 'fwe'];
            _yomi_to_roma['ふぉ'] = ['fo', 'fwo'];
            _yomi_to_roma['ふゃ'] = ['fya'];
            _yomi_to_roma['ふゅ'] = ['fyu'];
            _yomi_to_roma['ふょ'] = ['fyo'];
            _yomi_to_roma['ぶ'] = ['bu'];
            _yomi_to_roma['ぷ'] = ['pu'];
            _yomi_to_roma['へ'] = ['he'];
            _yomi_to_roma['べ'] = ['be'];
            _yomi_to_roma['ぺ'] = ['pe'];
            _yomi_to_roma['ほ'] = ['ho'];
            _yomi_to_roma['ぼ'] = ['bo'];
            _yomi_to_roma['ぽ'] = ['po'];
            _yomi_to_roma['ま'] = ['ma'];
            _yomi_to_roma['み'] = ['mi'];
            _yomi_to_roma['みぃ'] = ['myi'];
            _yomi_to_roma['みぇ'] = ['mye'];
            _yomi_to_roma['みゃ'] = ['mya'];
            _yomi_to_roma['みゅ'] = ['myu'];
            _yomi_to_roma['みょ'] = ['myo'];
            _yomi_to_roma['む'] = ['mu'];
            _yomi_to_roma['め'] = ['me'];
            _yomi_to_roma['も'] = ['mo'];
            _yomi_to_roma['ゃ'] = ['lya', 'xya'];
            _yomi_to_roma['や'] = ['ya'];
            _yomi_to_roma['ゅ'] = ['lyu', 'xyu'];
            _yomi_to_roma['ゆ'] = ['yu'];
            _yomi_to_roma['ょ'] = ['lyo', 'xyo'];
            _yomi_to_roma['よ'] = ['yo'];
            _yomi_to_roma['ら'] = ['ra'];
            _yomi_to_roma['り'] = ['ri'];
            _yomi_to_roma['りぃ'] = ['ryi'];
            _yomi_to_roma['りぇ'] = ['rye'];
            _yomi_to_roma['りゃ'] = ['rya'];
            _yomi_to_roma['りゅ'] = ['ryu'];
            _yomi_to_roma['りょ'] = ['ryo'];
            _yomi_to_roma['る'] = ['ru'];
            _yomi_to_roma['れ'] = ['re'];
            _yomi_to_roma['ろ'] = ['ro'];
            _yomi_to_roma['ゎ'] = ['xwa'];
            _yomi_to_roma['わ'] = ['wa'];
            _yomi_to_roma['ゐ'] = ['wyi'];
            _yomi_to_roma['ゑ'] = ['wye'];
            _yomi_to_roma['を'] = ['wo'];
            _yomi_to_roma['ん'] = ['nn', 'xn'];
            _yomi_to_roma['ゔ'] = ['vu'];
            _yomi_to_roma['ゔぁ'] = ['va'];
            _yomi_to_roma['ゔぃ'] = ['vi', 'vyi'];
            _yomi_to_roma['ゔぇ'] = ['ve', 'vye'];
            _yomi_to_roma['ゔぉ'] = ['vo'];
            _yomi_to_roma['ゔゃ'] = ['vya'];
            _yomi_to_roma['ゔゅ'] = ['vyu'];
            _yomi_to_roma['ゔょ'] = ['vyo'];
            _yomi_to_roma['ヵ'] = ['lka', 'xka'];
            _yomi_to_roma['ヶ'] = ['lke', 'xke'];
            _yomi_to_roma['ー'] = ['-'];
            _yomi_to_roma['、'] = [','];
            _yomi_to_roma['。'] = ['.'];
            _yomi_to_roma['a'] = ['a'];
            _yomi_to_roma['b'] = ['b'];
            _yomi_to_roma['c'] = ['c'];
            _yomi_to_roma['d'] = ['d'];
            _yomi_to_roma['e'] = ['e'];
            _yomi_to_roma['f'] = ['f'];
            _yomi_to_roma['g'] = ['g'];
            _yomi_to_roma['h'] = ['h'];
            _yomi_to_roma['i'] = ['i'];
            _yomi_to_roma['j'] = ['j'];
            _yomi_to_roma['k'] = ['k'];
            _yomi_to_roma['l'] = ['l'];
            _yomi_to_roma['m'] = ['m'];
            _yomi_to_roma['n'] = ['n'];
            _yomi_to_roma['o'] = ['o'];
            _yomi_to_roma['p'] = ['p'];
            _yomi_to_roma['q'] = ['q'];
            _yomi_to_roma['r'] = ['r'];
            _yomi_to_roma['s'] = ['s'];
            _yomi_to_roma['t'] = ['t'];
            _yomi_to_roma['u'] = ['u'];
            _yomi_to_roma['v'] = ['v'];
            _yomi_to_roma['w'] = ['w'];
            _yomi_to_roma['x'] = ['x'];
            _yomi_to_roma['y'] = ['y'];
            _yomi_to_roma['z'] = ['z'];
            _yomi_to_roma[','] = [','];
            _yomi_to_roma['.'] = ['.'];
            _yomi_to_roma['0'] = ['0'];
            _yomi_to_roma['1'] = ['1'];
            _yomi_to_roma['2'] = ['2'];
            _yomi_to_roma['3'] = ['3'];
            _yomi_to_roma['4'] = ['4'];
            _yomi_to_roma['5'] = ['5'];
            _yomi_to_roma['6'] = ['6'];
            _yomi_to_roma['7'] = ['7'];
            _yomi_to_roma['8'] = ['8'];
            _yomi_to_roma['9'] = ['9'];
            _yomi_to_roma[' '] = [' '];
            return _yomi_to_roma;
        }
        // 1単語のyomiを引数にそのチャンクの配列を返す
        function yomiToYomiChunk(_yomi){
            _yomiChunk = [];
            let i = 0;
            while(i < _yomi.length){
                // 「ん」か「っ」の時
                if(_yomi[i] === 'ん' || _yomi[i] === 'っ'){
                    if(i < _yomi.length - 2){
                        // 「っしゃ」とか
                        if(yomi_to_roma[_yomi[i+1] + _yomi[i+2]]){
                            _yomiChunk.push(_yomi[i] + _yomi[i+1] + _yomi[i+2]);
                            i += 2;
                        }
                        // 「っし」とか
                        else{
                            _yomiChunk.push(_yomi[i] + _yomi[i+1]);
                            i++;
                        }
                    }
                    // 「っし」とか
                    else if(i < _yomi.length - 1){
                        _yomiChunk.push(_yomi[i] + _yomi[i+1]);
                        i++;
                    }
                    // 文末が「ん」か「っ」の時
                    else{
                        _yomiChunk.push(_yomi[i]);
                    }
                }
                // 「ん」か「っ」でない時
                else{
                    if(i < _yomi.length - 1){
                        // 「しゃ」とか
                        if(yomi_to_roma[_yomi[i] + _yomi[i+1]]){
                            _yomiChunk.push(_yomi[i] + _yomi[i+1]);
                            i++;
                        }
                        // 「し」とか
                        else{
                            _yomiChunk.push(_yomi[i]);
                        }
                    }
                    // 「し」とか
                    else{
                        _yomiChunk.push(_yomi[i]);
                    }
                }
                i++;
            }
            return _yomiChunk;
        }
        // yomiChunkを引数にromaのチャンク配列が格納された配列を返す
        function yomiChunkToRomaChunk(_yomiChunk){
            let _romaChunk = [];
            const aiueony = ['a', 'i', 'u', 'e', 'o', 'n', 'y'];
            const aiueon = ['a', 'i', 'u', 'e', 'o', 'n'];
            for(const _yomi of _yomiChunk){
                var tmp = [];
                // 長さごとに見ていく
                if(_yomi.length === 1){
                    for(const r of yomi_to_roma[_yomi]){
                        tmp.push(r);
                    }
                }
                else if(_yomi.length === 2){
                    if(_yomi[0] === 'ん'){
                        // 「んし(nsi)」とか
                        for(const r of yomi_to_roma[_yomi[1]]){
                            if(!aiueony.includes(r[0])){
                                tmp.push('n' + r);
                            }
                        }
                        // 「ん,し(nn,si)」とか
                        for(const r0 of yomi_to_roma[_yomi[0]]){
                            for(const r1 of yomi_to_roma[_yomi[1]]){
                                tmp.push(r0 + r1);
                            }
                        }
                    }
                    else if(_yomi[0] === 'っ'){
                        // 「っし(ssi)」とか
                        for(const r of yomi_to_roma[_yomi[1]]){
                            if(!aiueon.includes(r[0])){
                                tmp.push(r[0] + r);
                            }
                        }
                        // 「っ,し(ltu,si)」とか
                        for(const r0 of yomi_to_roma[_yomi[0]]){
                            for(const r1 of yomi_to_roma[_yomi[1]]){
                                tmp.push(r0 + r1);
                            }
                        }
                    }
                    else{
                        // 「しゃ(sya)」とか
                        for(const r of yomi_to_roma[_yomi]){
                            tmp.push(r);
                        }
                        // 「し,ゃ(silya)」とか
                        for(const r0 of yomi_to_roma[_yomi[0]]){
                            for(const r1 of yomi_to_roma[_yomi[1]]){
                                tmp.push(r0 + r1);
                            }
                        }
                    }
                }
                else{
                    if(_yomi[0] === 'ん'){
                        // 「んしゃ(nsya)」とか
                        for(const r of yomi_to_roma[_yomi[1] + _yomi[2]]){
                            if(!aiueony.includes(r[0])){
                                tmp.push('n' + r);
                            }
                        }
                        // 「ん,しゃ(nnsya)」とか
                        for(const r0 of yomi_to_roma[_yomi[0]]){
                            for(const r1 of yomi_to_roma[_yomi[1] + _yomi[2]]){
                                tmp.push(r0 + r1);
                            }
                        }
                        // 「んし,ゃ(nsilya)」とか
                        for(const r0 of yomi_to_roma[_yomi[1]]){
                            for(const r1 of yomi_to_roma[_yomi[2]]){
                                if(!aiueony.includes(r0[0])){
                                    tmp.push('n' + r0 + r1);
                                }
                            }
                        }
                        // 「ん,し,ゃ(nnsilya)」とか
                        for(const r0 of yomi_to_roma[_yomi[0]]){
                            for(const r1 of yomi_to_roma[_yomi[1]]){
                                for(const r2 of yomi_to_roma[_yomi[2]]){
                                    tmp.push(r0 + r1 + r2);
                                }
                            }
                        }
                    }
                    else if(_yomi[0] === 'っ'){
                        // 「っしゃ(ssya)」とか
                        for(const r of yomi_to_roma[_yomi[1] + _yomi[2]]){
                            if(!aiueon.includes(r[0])){
                                tmp.push(r[0] + r);
                            }
                        }
                        // 「っし,ゃ(ssilya)」とか
                        for(const r0 of yomi_to_roma[_yomi[1]]){
                            for(const r1 of yomi_to_roma[_yomi[2]]){
                                if(!aiueon.includes(r0[0])){
                                    tmp.push(r0[0] + r0 + r1);
                                }
                            }
                        }
                        // 「っ,しゃ(ltusya)」とか
                        for(const r0 of yomi_to_roma[_yomi[0]]){
                            for(const r1 of yomi_to_roma[_yomi[1]]){
                                tmp.push(r0 + r1);
                            }
                        }
                        // 「っ,し,ゃ(ltusilya)」とか
                        for(const r0 of yomi_to_roma[_yomi[0]]){
                            for(const r1 of yomi_to_roma[_yomi[1]]){
                                for(const r2 of yomi_to_roma[_yomi[2]]){
                                    tmp.push(r0 + r1 + r2);
                                }
                            }
                        }
                    }
                }
                _romaChunk.push(tmp);
            }
            return _romaChunk;
        }
        function init(){
            // 値のリセット
            sentence.innerHTML = '';
            typed.innerHTML = '';
            missed.innerHTML = '';
            untyped.innerHTML = '';
            current_time.innerHTML = 'タイム：0.000';
            _type_count.innerHTML = 'タイプ数：0';
            _miss_count.innerHTML = 'ミス数：0';
            yomiChunk = [];
            romaChunk = [];
            word_count = 0;
            chunk_count = 0;
            type_count = 0;
            miss_count = 0;
            current_key = '';
            typed_roma = [];
            is_clear = false;
            result_table.classList.remove('fadein');
            result_table.style.visibility = 'hidden';
            time_hidden.value = "";
            miss_hidden.value = "";
            clearInterval(timer);
            words = createRandomWords();
            for(let i=0; i<words.length; i++){
                yomiChunk.push(yomiToYomiChunk(words[i]['yomi']));
                romaChunk.push(yomiChunkToRomaChunk(yomiChunk[i]));
            }
            setSentence(words);
            for(const r of romaChunk[0]){
                untyped.innerHTML += r[0];
            }
        }
        // 現在のtimeを表示
        function setCurrentTime(){
            let tmp = (performance.now() - start_time) / 1000;
            current_time.innerHTML = 'タイム：' + tmp.toFixed(3);
            if(tmp.toFixed(3) >= 1000){
                alert('時間がかかりすぎです。');
                init();
            }
        }
        // timeを引数にランクを返す関数
        function getRank(_time){
            let rank_top = [];
            for(let i=38; i>=4; i--){
                rank_top.push('S+' + i);
            }
            rank_top.push('SSS');
            rank_top.push('SS');
            rank_top.push('S');
            rank_top.push('A+');
            rank_top.push('A');
            let rank_bottom = ['A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'E+', 'E', 'E-', 'F+', 'F', 'F-'];
            if(_time <= 80){
                return rank_top[Math.floor(_time / 2)];
            }
            else if(_time <= 240){
                return rank_bottom[Math.floor((_time - 80) / 10)];
            }
            else{
                return 'F-';
            }
        }
        // キー押下イベント
        document.body.addEventListener('keydown', event => {
            if(event.keyCode === 27){
                init();
            }
            if(!is_clear){
                if(event.keyCode === 32){
                    checkInput(' ');
                    event.preventDefault();
                }
                else if((48 <= event.keyCode && event.keyCode <= 90) || (186 <= event.keyCode && event.keyCode <= 192) || (219 <= event.keyCode && event.keyCode <= 222) || event.keyCode === 226){
                    checkInput(event.key);
                }
            }
            else{
                if(event.keyCode === 13 && ranking_button.disabled === false){
                    ranking_button.click()
                }
                if(event.keyCode === 32){
                    event.preventDefault();
                }
            }
        });
        // グローバル変数
        const words_obj = @json($words);
        const yomi_to_roma = getYomiToRoma();
        let sentence = document.querySelector('#sentence');
        let typed = document.querySelector('#typed');
        let missed = document.querySelector('#missed');
        let untyped = document.querySelector('#untyped');
        // let current_key_count = document.querySelector('#current-key-count');
        let current_time = document.querySelector('#current-time');
        let result_table = document.querySelector('#result-table');
        // let rank_result = document.querySelector('#rank-result');
        // let time_result = document.querySelector('#time-result');
        let ranking_button = document.querySelector('#ranking-button');
        let retry_button = document.querySelector('#retry-button');
        let time_hidden = document.querySelector('#time-hidden');
        let miss_hidden = document.querySelector('#miss-hidden');
        let _type_count = document.querySelector('#type-count');
        let _miss_count = document.querySelector('#miss-count');
        let rank_span = document.querySelector('#rank-span');
        // init()で初期化
        let word_span = [];
        let words = [];
        let yomiChunk = [];     // [0]=>(きょ,う)
        let romaChunk = [];     // [0]=>((kyo,kilyo,kixyo),(u,wu,whu))
        let word_count = 0;
        let chunk_count = 0;
        let type_count = 0;
        let miss_count = 0;
        let current_key = '';
        let typed_roma = [];
        let start_time = 0;
        let end_time = 0;
        let timer;
        let time = 0;
        let is_clear = false;
        // 効果音
        let miss_sound = new Audio('mp3/miss.mp3');
        let clear_sound = new Audio('mp3/clear.mp3');
        // イベントの追加
        // raning_button.addEventListener('click', );
        retry_button.addEventListener('click', init);
        init();

    </script>
@endsection