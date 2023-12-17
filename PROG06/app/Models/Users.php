<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
  use HasFactory;
  protected $fillable = [
    'username', 'fullname', 'password', 'role', 'phone', 'email', 'website', 'description', 'avatar'
  ];

  public function messages()
  {
    return $this->hasMany(Message::class);
  }
}
