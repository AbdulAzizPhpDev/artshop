<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $int)
 */
class VideoSection extends Model
{
    protected $table = 'video_sections';

    public function videos()
    {
        return $this->hasMany(Video::class, 'section_id');
    }
}
