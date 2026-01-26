<?php

namespace App;

use App\Helpers\Util;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\File;

use EloquentFilter\Filterable;

class Property extends Model implements HasMedia
{
    use HasMediaTrait, Filterable;

//    use Sluggable;
    use HasSlug;

    protected $casts = [
        'is_public' => 'boolean',
        'show_phone' => 'boolean',
        'show_website' => 'boolean',
        'send_message' => 'boolean',
    ];

    protected $with = ['media'];

    protected $fillable = [
        'slug',
        'title',
        'category_id',
//        'city_id',
        'country_id',
        'is_public',
        'city',
        'state',
        'business_name',
        'social_network',
        'action_id',
        'status',
        'visitors',
        'website',
        'image_path',
        'short_description',
        'description',
        'comment',
        'phone',
        'email',
        'address',
        'show_email',
        'show_website',
        'show_phone',
        'serial_number',
        'google_map',
        'send_message',
        'start_date',
        "expire_date",
        "whatsapp_number",
    ];

    protected $dates = ['created_at', 'updated_at', 'expire_date', 'start_date'];

//    public function modelFilter()
//    {
//        return $this->provideFilter(\App\ModelFilters\PropertyFilter::class);
//    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->with('parent');
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'property_id', 'id');
    }

    public function getIsOwnerAttribute()
    {
        if (\Auth::check()) {
            if (\Auth::user()->id == $this->user_id) {
                return true;
            }
            return false;
        }
    }

    public function getTotalOwnerAttribute()
    {
        if (\Auth::check()) {
            return $this->where('user_id', \Auth::user()->id)->count();
        }
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function registerMediaCollections()
    {
//        $this->addMediaCollection('thumb')
//            ->singleFile();

        $this->addMediaCollection('photo')
            ->singleFile()
            ->useDisk('s3')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->quality(50)
                    ->sharpen(10)
                    ->performOnCollections('photo');
                $this
                    ->addMediaConversion('medium')
                    ->width(400)
                    ->height(400)
                    ->sharpen(10)
                    ->quality(60);
                $this->addMediaConversion('large')
                    ->width(1024);
            });
        $this->addMediaCollection('gallery')
            ->useDisk('s3')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->useFallbackUrl('/img/img.png')
            ->useFallbackPath(public_path('/img/img.png'))
//              ->acceptsFile(function (File $file) {
//                  return $file->mimeType === 'image/*';
//              })
            ->onlyKeepLatest(6)
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->sharpen(10)
                    ->quality(50)
                    ->performOnCollections('photo');
                $this
                    ->addMediaConversion('medium')
                    ->width(400)
                    ->height(400)
                    ->sharpen(10)
                    ->quality(60);
                $this->addMediaConversion('large')
                    ->width(1024)
                    ->sharpen(10);
            });
    }

    public function getTimeLeftAttribute()
    {
        $expire = Carbon::parse($this->expire_date);
        $left = $expire->diffForHumans();
//        diffForhumans
        return $left;
    }

    public function getThumbImageAttribute(): string
    {
        $photos = $this->getMedia('photo');

        $thumbnail = $photos->isEmpty()
            ? '/img/img.png'
            : $photos->first()->getUrl("medium");

        return $thumbnail;
    }

    /**
     *  #IMPORTANT !!!
     *  Todos los anuncios debe llamar este para presentar la primera imagen en toda la pagina
     *  donde no sea un carrousel de imagenes.
     *  Esta funciona es importante , se llama una ves instanciado el anuncio
     *  $product = Product::find(1);
     *  echo $product->ImageThumb->url;
     */
    public function getImageThumbAttribute()
    {
        //slug = dealers
        if ($file = $this->getMedia('gallery')->first()) {
            if ($file) {
                $file->url = $file->getUrl();
                $file->thumbnail = $file->getUrl('thumb');
            }
        } else {
            $file = new \stdClass();
            $file->url = asset("images/logos/logo-icon-500x500.png");
            $file->thumbnail = asset('images/logos/logo-icon-200x200.png');
        }

        return $file;
    }

    public function getExcerptAttribute()
    {
        return Util::get_excerpt(Util::get_clean($this->description), '30', '...');
    }

    public  function  isPublic()
    {
        if((int)$this->is_public) {
            return true;
        }

        return true;
    }

}
