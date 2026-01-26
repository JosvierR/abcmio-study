<?php

namespace App\Repositories;

use App\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;

class PropertyRepository
{

    public function create(Request $request): Property
    {
        $user = \Auth::user();
        $property = new Property();
        $property->fill($request->all());
        $property->is_public = 0;
        $property->status = 'enable';
        $property->send_message = 1;
        $user->properties()->save($property);

//        if ($request->hasFile('picture')) {
//            $property->addMedia($request->picture)->toMediaCollection("photo");
//        }

        return $property;
    }

    public function update(Property $property, Request $request): Property
    {
        $property->fill($request->all());
        $property->show_email = $request->has('show_email') ? true : false;
        $property->send_message = $request->has('send_message') ? true : false;
//        $property->status = $request->has('status') ? 'enable' : 'disable';
//        $property->status = 'enable';
        $property->send_message = 1;
        $property->update();

        if ($request->hasFile('picture')) {
            $property->addMedia($request->picture)->toMediaCollection("photo");
        }

        $property->refresh();
        return $property;
    }

    public function delete(Property $property): bool
    {
        if ($property->user_id === auth()->user()->id) {
            $property->delete();
            return true;
        }
        return false;
    }

    public function make($action = null, $payload = [], Property $property): Property
    {
        switch ($action) {
            case 'publish':
                return $this->publish($property, $payload);
            case 'extend':
                return $this->extend($property, $payload);
            case 'private':
                return $this->private($property, $payload);
            case null:
            default:
                return $property;
        }
    }

    private function publish(Property $property, $payload = []): Property
    {

        if ($this->isOwner($property)) {
            $dates = [
                'is_public' => true,
                'start_date' => Carbon::now()->toDateString(),
                'expire_date' => Carbon::now()->addDays((int)$payload['days'])->toDateString()
            ];
            $property->update($dates);
            $property->refresh();

            $this->updateUserCredits($payload);
        }
        return $property;
    }

    private function extend(Property $property, $payload = []): Property
    {
        if ($this->isOwner($property)) {
            $dates = [
                'is_public' => true,
                'expire_date' => Carbon::createFromFormat('d/m/Y',
                    $property->expire_date->format("d/m/Y"))->addDays((int)$payload->days)->toDateString()
            ];
            $property->update($dates);
            $property->refresh();
            $this->updateUserCredits($payload);
        }
        return $property;
    }

    private function private(Property $property, $payload = []): Property
    {
        if ($this->isOwner($property)) {
            $dates = [
                'is_public' => false,
                'expire_date' => null
            ];
            $property->update($dates);
            $property->refresh();
//            $this->updateUserCredits($payload);
        }
        return $property;
    }



    private function isOwner(Property $property): bool
    {
        if (auth()->check() && auth()->user()->id == $property->user->id) {
            return true;
        }
        return false;
    }

    private function updateUserCredits($payload = []) : void
    {
        if(isset($payload['days']) && (int)$payload['days'] > 0) {
            $user = auth()->user();
            $total = $user->TotalCredits - (int) $payload['days'];
            $user->update(['credits' => $total]);
            $user->refresh();
        }
    }

    public function getPropertyVisitors(Property $property)
    {
        return $property->visitors;
    }

    public  function updatePropertyVisitors(Property  $property, $total = null) : Property
    {
        if(!is_null($total)) {
            $property->update(['visitors' => $total]);
        }

        return $property->refresh();
    }
}
