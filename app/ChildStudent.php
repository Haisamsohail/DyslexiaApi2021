<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ChildStudent extends Model
{
    protected $fillable = [
                            'ParentID', 
                            'FirstName',
                            'LastName',
                            'Age',
                            'Gender',
                            'email',
                            'password',
                            'profilepicture',
                            'Status'
                          ];
}
