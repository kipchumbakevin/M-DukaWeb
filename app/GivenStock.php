<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GivenStock extends Model
{
    protected $fillable = ['quantity','item_id'];
}
