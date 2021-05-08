<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $words = \App\Word::all();

        $login_message1 = '';
        $login_message2 = '';

        if(Auth::check()){
            $date = date('G');
            if($date < 4 || 18 <= $date){
                $login_message1 = 'こんばんは。';
            }
            else if($date < 10){
                $login_message1 = 'おはようございます。';
            }
            else{
                $login_message1 = 'こんにちは。';
            }
            $tmp = array(
                '今日も張り切っていきましょう。',
                'ミスが多くなってきたら休憩しましょう。',
                '今日も元気にいきましょう。',
                '疲れてきたら休憩しましょう。',
                '少しずつコツコツと。',
                'よいタイピングはよい睡眠から。'
            );
            $rand = 0;
            if(mt_rand(1, 4) === 1){
                $rand = mt_rand(1, count($tmp) - 1);
            }
            $login_message2 = $tmp[$rand];
        }
        
        return view('index', ['words' => $words, 'login_message1' => $login_message1, 'login_message2' => $login_message2]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
