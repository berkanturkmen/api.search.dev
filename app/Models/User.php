<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $fillable = ['EMAIL', 'PASSWORD', 'API_TOKEN', 'PERMANENT_STORAGE', 'TEMP_STORAGE'];

	protected $hidden = ['PASSWORD', 'TEMP_STORAGE'];
}
