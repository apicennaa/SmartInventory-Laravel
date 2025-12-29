<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomingGood extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'incoming_goods';

    protected $fillable = [
        'product',
        'incoming',
        'category',
        'supplier',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'incoming' => 'integer',
    ];
}

