<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObscoleteStock extends Model
{
    protected $fillable = ['quantity','item_id'];
}
