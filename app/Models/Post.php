<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PostObserver::class])]
class Post extends Model
{
    //
}
