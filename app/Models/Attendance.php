<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;


class Attendance extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function generateUniqueBarcode()
    {
        do {
            $barcode = Uuid::uuid4()->toString();
        } while (Attendance::where('barcode_hour_came', $barcode)->orWhere('barcode_home_time', $barcode)->exists());

        return $barcode;
    }
}
