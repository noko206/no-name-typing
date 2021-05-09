<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $result = new \App\Result;
        $result->time = $request->time;
        $result->miss = $request->miss;
        $result->user_id = $request->user_id;
        $result->save();

        $result = \App\Result::where('user_id', $request->user_id)->orderBy('created_at', 'desc')->first();

        return redirect()->route('results.show', $result->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!Auth::check()){
            return redirect('/');
        }
        $request = \App\Result::find($id);
        if($request->user_id !== Auth::id()){
            return redirect('/');
        }
        $latest_result = \App\Result::orderBy('created_at', 'desc')->first();
        if($request->id !== $latest_result->id){
            return redirect('/');
        }
        $user_junni = 0;
        $user_num = 0;
        $my_junni = 0;
        $my_num = 0;
        $data = \App\Result::whereRaw('time <= :time', ['time' => $request->time])->orderBy('time')->get();
        $results = array();
        $is_add = array();
        foreach($data as $d){
            if(!array_key_exists($d['user_id'], $is_add) || $d['id'] == $id){
                $is_add[$d['user_id']] = true;
                array_push($results, $d);
            }
        }
        $user_junni = count($results);
        $user_num = \App\Result::distinct('user_id')->count('user_id');
        $user_num++;
        $my_junni = \App\Result::whereRaw('user_id = :user_id and time <= :time', ['user_id' => $request->user_id, 'time' => $request->time])->count();
        $my_num = \App\Result::where('user_id', $request->user_id)->count();
        
        if($my_num === 1){
            $user_num -= 1;
        }
        
        return view('result', ['user_junni' => $user_junni, 'user_num' => $user_num, 'my_junni' => $my_junni, 'my_num' => $my_num]);
        // // return redirect('/');
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

    public function showRanking(){
        // resultsテーブルから取得
        $data = \App\Result::orderBy('time')->get();
        $results = array();
        $is_add = array();
        foreach($data as $d){
            if(!array_key_exists($d['user_id'], $is_add)){
                $is_add[$d['user_id']] = true;
                array_push($results, $d);
            }
        }
        // usersテーブルから取得
        $data = \App\User::all();
        $users = array();
        foreach($data as $d){
            $users[$d['id']] = $d;
        }
        // ランク配列を作成
        $rank_top = array();
        for($i=38; $i>=4; $i--){
            array_push($rank_top, 'S+'.$i);
        }
        array_push($rank_top, 'SSS');
        array_push($rank_top, 'SS');
        array_push($rank_top, 'S');
        array_push($rank_top, 'A+');
        array_push($rank_top, 'A');
        $rank_bottom = ['A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'E+', 'E', 'E-', 'F+', 'F', 'F-'];
        return view('ranking', ['results' => $results, 'users' => $users, 'rank_top' => $rank_top, 'rank_bottom' => $rank_bottom]);
    }

    public function showRecord($id){
        // resultsテーブルから取得
        $results = \App\Result::where('user_id', $id)->orderBy('time')->get();
        // usersテーブルから取得
        $user = \App\User::where('id', $id)->get();
        // ランク配列を作成
        $rank_top = array();
        for($i=38; $i>=4; $i--){
            array_push($rank_top, 'S+'.$i);
        }
        array_push($rank_top, 'SSS');
        array_push($rank_top, 'SS');
        array_push($rank_top, 'S');
        array_push($rank_top, 'A+');
        array_push($rank_top, 'A');
        $rank_bottom = ['A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'E+', 'E', 'E-', 'F+', 'F', 'F-'];
        // 平均のランク、タイム、kps、kpm、ミス、正誤率
        $play_count = count($results);
        $top1 = array();
        $top15 = array();
        $top99 = array();
        if($play_count >= 1){
            $top1['time'] = $results[0]['time'];
            $top1['miss'] = $results[0]['miss'];
            $top1['created_at'] = $results[0]['created_at'];

            $top1['kps'] = 400 / $top1['time'];
            $top1['kpm'] = 60 * $top1['kps'];
            $top1['accuracy'] = 100 * 400 / (400 + $top1['miss']);
        }
        if($play_count >= 15){
            $top15['time'] = $results[0]['time'];
            $top15['miss'] = $results[0]['miss'];
            for($i=1; $i<15; $i++){
                $top15['time'] += $results[$i]['time'];
                $top15['miss'] += $results[$i]['miss'];
            }
            $top15['time'] /= 15;
            $top15['miss'] /= 15;
            
            $top15['kps'] = 400 / $top15['time'];
            $top15['kpm'] = 60 * $top15['kps'];
            $top15['accuracy'] = 100 * 400 / (400 + $top15['miss']);
        }
        if($play_count >= 99){
            $top99['time'] = $results[0]['time'];
            $top99['miss'] = $results[0]['miss'];
            for($i=1; $i<99; $i++){
                $top99['time'] += $results[$i]['time'];
                $top99['miss'] += $results[$i]['miss'];
            }
            $top99['time'] /= 99;
            $top99['miss'] /= 99;
            
            $top99['kps'] = 400 / $top99['time'];
            $top99['kpm'] = 60 * $top99['kps'];
            $top99['accuracy'] = 100 * 400 / (400 + $top99['miss']);
        }
        return view('user', ['play_count' => $play_count, 'top1' => $top1, 'top15' => $top15, 'top99' => $top99, 'rank_top' => $rank_top, 'rank_bottom' => $rank_bottom, 'results' => $results, 'user' => $user]);
    }
}
