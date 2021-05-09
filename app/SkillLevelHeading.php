<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class SkillLevelHeading extends Model
{
	protected $fillable = ['LevelNumber', 'LevelHeading'];

	public function chapter()
	{
	    return $this->hasMany('App\chapter');
	}


    public function passingcriteria()
    {
        return $this->hasOne(passingcriteria::class, 'skilllevelheading_id', 'id');
    }


    public function studentactivelevel()
    {
        return $this->hasOne(studentactivelevel::class, 'skilllevelheading_id', 'id');
    }
}
