<?php
namespace App\Traits;

trait RelatesToTeams
{
    public function scopeForCurrentTeam($query)
    {
        return $query->where('team_id', auth()->user()->currentTeam->id);
    }
 
}
