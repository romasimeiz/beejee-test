<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $timestamps=[];

    protected $fillable = ['name', 'email', 'text', 'picture', 'status'];

    public function getPictureUrlAttribute()
    {
        return "/media/" . $this->picture;
    }

}