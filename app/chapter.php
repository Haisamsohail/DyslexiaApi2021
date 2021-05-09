<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chapter extends Model
{
	//protected $fillable = ['chapter', 'levelId'];

	protected $guarded = [];

    public function words()
    {
        return $this->hasMany(word::class, 'chapter_id', 'id');
    }

    public function videos()
    {
        return $this->hasMany(video::class, 'chapter_id', 'id');
    }
}
