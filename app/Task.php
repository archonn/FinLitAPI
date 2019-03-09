<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
    	'name', 'reward', 'description', 'icon'
    ];

    protected $attributes = [
    	'icon' => ''
    ];
}
