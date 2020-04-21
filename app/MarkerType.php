<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarkerType extends Model
{
    protected $fillable = [
    	'name',
    	'icon'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by'
    ];

}
