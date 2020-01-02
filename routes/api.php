<?php

use Illuminate\Http\Request;

Route::get('/getSummoner/{region}/{summonerName}', 'RiotController@getSummoner');
Route::get('/updateSummoner/{region}/{summonerName}', 'RiotController@updateSummoner');
Route::get('/getMatch/{region}/{matchId}', 'RiotController@getMatch');
