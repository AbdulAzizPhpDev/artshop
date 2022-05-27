<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static paginate(int $paginate)
 */
class Video extends Model
{
    protected $table = 'videos';

    public function getSection()
    {
        return $this->hasOne(VideoSection::class, 'id', 'section_id');
    }

    public function like()
    {
        return $this->hasMany(VideoLike::class, 'video_id');
    }

    public function count()
    {
        return $this->hasMany(VideoCount::class, 'video_id');
    }

}
