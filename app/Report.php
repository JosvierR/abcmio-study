<?php

namespace App;

use App\Interfaces\ReportInterface;
use Illuminate\Database\Eloquent\Model;

class Report extends Model implements ReportInterface
{
    protected $fillable = ['user_id', 'property_id', 'option_id', 'description', 'status'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function option()
    {
        return $this->hasOne(ReportOption::class, 'id', 'option_id');
    }

    public function getStatusLabelAttribute()
    {
        $arr = ['pending' => 'Pendiente', 'resolved' => 'Resuelto', 'cancelled' => 'Cancelado'];
        return $arr[$this->status] ?? 'Indefinido';
    }

    public function getOptionLabelAttribute()
    {
        return self::LABELS[self::OPTIONS[$this->option_id]] ?? 'Unknown';
    }

    public function getTypeBandAttribute()
    {
        return self::BANDS[self::OPTIONS[$this->option_id]] ?? '';
    }
}
