<?php

namespace Modules\Draws\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Draws\Database\Factories\DrawsFactory;

class Draws extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): DrawsFactory
    // {
    //     // return DrawsFactory::new();
    // }
}