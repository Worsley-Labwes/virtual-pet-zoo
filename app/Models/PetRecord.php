<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// One row in the `pets` table. It only stores data;
// the behaviour (feed, play, speak...) lives in the App\Zoo classes.
class PetRecord extends Model
{
    protected $table = 'pets';
}
