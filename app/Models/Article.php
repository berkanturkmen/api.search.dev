<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
	protected $table = 'Articles';

	protected $fillable = ['ABSTRACT', 'CATEGORY', 'DATE', 'SOURCE', 'TITLE', 'URL', 'UUID'];

	protected $guarded = [];
}
