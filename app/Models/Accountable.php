<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accountable extends Model
{
    use HasFactory;

    protected $table = 'accountable';

    protected $fillable = [
        'person_accnt'
    ];
}
