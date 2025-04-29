<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceInformation extends Model
{
    protected $fillable=[
        'service_id',
        'description',
        'user_id',
        'location_id',
        'image_id',
    ];

    public function service(): BelongsTo{
        return $this->belongsTo(Service::class);
    }
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function location(): BelongsTo{
        return $this->belongsTo(Location::class);
    }
    public function image(): BelongsTo{
        return $this->belongsTo(Image::class);
    }
}
