<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarcodeScan extends Model
{
    use HasFactory;
    protected $primaryKey = 'barcode';
    public $incrementing = false;
    protected $guarded = [];
}
