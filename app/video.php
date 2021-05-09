<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class video extends Model
{
     protected $guarded = [];
     protected $fillable = ['chapter_id', 'VideoNumber','VideoTitle'];

    protected $attributes = [
        'Status' => 1
    ];

    public function getActiveAttribute($attribute)
    {
        return $this->activeOptions()[$attribute];
    }
    
    public function scopeActive($query)
    {
        return $query->where('Status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('Status', 0);
    }

    public function chapter()
    {
        return $this->belongsTo(chapter::class, 'chapter_id', 'id');
    }

    public function activeOptions()
    {
        return [
            1 => 'Active',
            0 => 'Inactive',
            2 => 'In-Progress'
        ];
    }
}
