<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
  use HasFactory;
  protected $table = 'submission';
  protected $fillable = [
    'description', 'submission', 'user_id', 'assignment_id'
  ];
}
