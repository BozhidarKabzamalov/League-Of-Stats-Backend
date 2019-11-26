<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Summoner;
use App\Match;

class RiotController extends Controller
{
    public function getSummoner($summonerName){

        $summoner = Summoner::where('summoner_name', $summonerName)->first();

        if ($summoner === null) {
            $apiKey = env("RIOT_API_KEY");
            $region = env("EUW");

            $getSummonerInfo = file_get_contents($region . "/lol/summoner/v4/summoners/by-name/" . $summonerName . "?api_key=" . $apiKey);
            $summonerInfo = json_decode($getSummonerInfo);

            $getSummonerLeague = file_get_contents($region . "/lol/league/v4/entries/by-summoner/" . $summonerInfo->id . "?api_key=" . $apiKey);
            $summonerLeague = json_decode($getSummonerLeague);

            $getSummonerMatches = file_get_contents($region . "/lol/match/v4/matchlists/by-account/" . $summonerInfo->accountId . "?beginIndex=0&endIndex=10&api_key=" . $apiKey);
            $summonerMatches = json_decode($getSummonerMatches);

            $matchesGET = [];

            foreach ($summonerMatches->matches as $match) {
                $url = $region . "/lol/match/v4/matches/" . $match->gameId . "?api_key=" . $apiKey;
                array_push($matchesGET, $url);
            }

            $curly = array();
            $mh = curl_multi_init();

            foreach ($matchesGET as $id => $d) {
                $curly[$id] = curl_init();

                $url = $d;
                curl_setopt($curly[$id], CURLOPT_URL,            $url);
                curl_setopt($curly[$id], CURLOPT_HEADER,         0);
                curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, 0);

                curl_multi_add_handle($mh, $curly[$id]);
            }

            $running = null;
            do {
                curl_multi_exec($mh, $running);
            } while ($running > 0);

            foreach($curly as $id => $c) {
                $summonerMatches->matches[$id]->details = json_decode(curl_multi_getcontent($c));
                curl_multi_remove_handle($mh, $c);
            }

            curl_multi_close($mh);

            $summoner = new Summoner();
            $summoner->summoner_name = $summonerName;
            $summoner->summoner_info = json_encode($summonerInfo);
            $summoner->summoner_league = json_encode($summonerLeague);
            $summoner->summoner_matches = json_encode($summonerMatches);

            $summoner->save();
        } else {
            $summonerInfo = json_decode($summoner->summoner_info);
            $summonerLeague = json_decode($summoner->summoner_league);
            $summonerMatches = json_decode($summoner->summoner_matches);
        }

        return response()->json([
            'summonerInfo' => $summonerInfo,
            'summonerLeague' => $summonerLeague,
            'summonerMatches' => $summonerMatches,
        ], 201);

    }

    public function updateSummoner($summonerName){

        $apiKey = env("RIOT_API_KEY");
        $region = env("EUW");

        $getSummonerInfo = file_get_contents($region . "/lol/summoner/v4/summoners/by-name/" . $summonerName . "?api_key=" . $apiKey);
        $summonerInfo = json_decode($getSummonerInfo);

        $getSummonerLeague = file_get_contents($region . "/lol/league/v4/entries/by-summoner/" . $summonerInfo->id . "?api_key=" . $apiKey);
        $summonerLeague = json_decode($getSummonerLeague);

        $getSummonerMatches = file_get_contents($region . "/lol/match/v4/matchlists/by-account/" . $summonerInfo->accountId . "?beginIndex=0&endIndex=10&api_key=" . $apiKey);
        $summonerMatches = json_decode($getSummonerMatches);

        $matchesGET = [];

        foreach ($summonerMatches->matches as $match) {
            $url = $region . "/lol/match/v4/matches/" . $match->gameId . "?api_key=" . $apiKey;
            array_push($matchesGET, $url);
        }

        $curly = array();
        $mh = curl_multi_init();

        foreach ($matchesGET as $id => $d) {
            $curly[$id] = curl_init();

            $url = $d;
            curl_setopt($curly[$id], CURLOPT_URL,            $url);
            curl_setopt($curly[$id], CURLOPT_HEADER,         0);
            curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, 0);

            curl_multi_add_handle($mh, $curly[$id]);
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        foreach($curly as $id => $c) {
            $summonerMatches->matches[$id]->details = json_decode(curl_multi_getcontent($c));
            curl_multi_remove_handle($mh, $c);
        }

        curl_multi_close($mh);

        $summoner = Summoner::where('summoner_name', $summonerName)->first();
        $summoner->summoner_name = $summonerName;
        $summoner->summoner_info = json_encode($summonerInfo);
        $summoner->summoner_league = json_encode($summonerLeague);
        $summoner->summoner_matches = json_encode($summonerMatches);

        $summoner->save();

        return response()->json([
            'summonerInfo' => $summonerInfo,
            'summonerLeague' => $summonerLeague,
            'summonerMatches' => $summonerMatches,
        ], 201);

    }

    public function getMatch($matchId){
        $apiKey = env("RIOT_API_KEY");
        $region = env("EUW");

        $matchExists = Match::where('match_id', $matchId)->first();

        //$getMatchTimeline = file_get_contents($region . "/lol/match/v4/timelines/by-match/" . $matchId . "?api_key=" . $apiKey);
        //$matchTimeline = json_decode($getMatchTimeline);

        if ($matchExists === null) {
            $getMatchInfo = file_get_contents($region . "/lol/match/v4/matches/" . $matchId . "?api_key=" . $apiKey);
            $matchInfo = json_decode($getMatchInfo);

            $match = new Match();
            $match->match_id = $matchId;
            $match->match_info = json_encode($matchInfo);

            $match->save();
        } else {
            $matchInfo = json_decode($matchExists->match_info);
        }

        return response()->json([
            'match' => $matchInfo,
            //'matchTimeline' => $matchTimeline
        ], 201);
    }

}
