<?php

namespace App\Observers;

use App\Property;

class PropertyObserver
{
    /**
     * Handle the property "created" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function created(Property $property)
    {
        //
    }

    /**
     * Handle the property "updated" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function updated(Property $property)
    {
//        dd($property->getAttributes());
//        $property->short_description = \Str::limit(trim($property->short_description),100);

//        $i = 1;
//        $slug = Str::slug($property->title);
//        while(Property::where('slug',$slug)->first())
//            $slug = Str::slug($property->title)."-".$i++;
//        $property->slug = $slug;
//        $property->save();

    }

    /**
     * Handle the property "deleted" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function deleted(Property $property)
    {
        //
    }

    /**
     * Handle the property "restored" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function restored(Property $property)
    {
        //
    }

    /**
     * Handle the property "force deleted" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function forceDeleted(Property $property)
    {
        //
    }
}
