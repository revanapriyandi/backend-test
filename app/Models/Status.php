<?php

namespace App\Models;

use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Status extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hash', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPublishedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function images()
    {
        return $this->hasMany(Media::class, 'model_id')
            ->where('collection_name', 'images');
    }
}
