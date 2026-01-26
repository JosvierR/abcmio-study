<?php


namespace App\Services;


use App\Report;
use App\ReportOption;
use App\Property;

class ReportService
{

    public function getOptions()
    {
        return ReportOption::where('status', 'enabled')->orderBy('name', 'asc')->get();
    }

    public function isReported(Property $property)
    {
        $user = auth()->user();
        if(Report::where(['user_id' => $user->id])->where(['property_id' => $property->id])->where('status', 'pending')->first()){
            return true;
        }
        return false;
    }

    public function search($searchQuery = null)
    {
        return Report::with(['property', 'user'])
            ->whereHas('property', function($query) {
                return $query->where('is_public', true);
            })
            ->where(function($query) use ($searchQuery) {
                $query->when($searchQuery && !empty(trim($searchQuery)), function($query) use ($searchQuery) {
                  $query->where('id', trim($searchQuery))
                        ->orWhere('title', 'like', '%' . $searchQuery . '%');
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }

    public function getTotal($searchQuery = null)
    {
        return Report::with(['property', 'user'])
            ->whereHas('property', function($query) {
                return $query->where('is_public', true);
            })
            ->where(function($query) use ($searchQuery) {
                $query->when($searchQuery && !empty(trim($searchQuery)), function($query) use ($searchQuery) {
                    $query->where('id', trim($searchQuery))
                        ->orWhere('title', 'like', '%' . $searchQuery . '%');
                });
            })
            ->count();
    }
}