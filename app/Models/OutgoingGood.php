<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutgoingGood extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'outgoing_goods';

    protected $fillable = [
        'product',
        'category',
        'outgoing',
        'store',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'outgoing' => 'integer',
    ];
}

