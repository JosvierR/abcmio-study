<?php

namespace App\Repositories;

use App\Visitor;

class VisitorRepository
{
    public function getVisitorCountByVisitorIP(string $ip)
    {
        if(!Visitor::where('ip', $ip)->exists() ) {
            Visitor::create(['ip' => $ip, 'visits' => 1]);
            $totalVisitors = Visitor::count('visits');
        } else {
            $totalVisitors = Visitor::count('visits');
        }

        return $totalVisitors;
    }
}