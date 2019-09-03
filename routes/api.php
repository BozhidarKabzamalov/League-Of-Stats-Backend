<?php

use Illuminate\Http\Request;

Route::get('/getSummoner/{summonerName}', 'RiotController@getSummoner');
Route::get('/updateSummoner/{summonerName}', 'RiotController@updateSummoner');
Route::get('/getMatch/{matchId}', 'RiotController@getMatch');
