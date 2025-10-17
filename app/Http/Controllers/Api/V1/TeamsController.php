<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\TeamResource;
use App\Models\User;
use App\Traits\ApiResponses;

class TeamsController extends ApiController
{
    use ApiResponses;
    /**
     * return a list of teams members
     */
    public function index()
    {
        $teams = TeamResource::collection(User::where('isTeam', '=', '1')->get());
        return $this->ok('success',['teamsMembers' => $teams]);
    }
}
