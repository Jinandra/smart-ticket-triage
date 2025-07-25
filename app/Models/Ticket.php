<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['subject', 'body', 'status', 'note', 'explanation', 'category', 'confidence'];
}
