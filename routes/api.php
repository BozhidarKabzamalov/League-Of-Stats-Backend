<?php

use Illuminate\Http\Request;

Route::get('/getSummoner/{region}/{summonerName}', 'RiotController@getSummoner');
Route::get('/updateSummoner/{summonerName}', 'RiotController@updateSummoner');
Route::get('/getMatch/{matchId}', 'RiotController@getMatch');
