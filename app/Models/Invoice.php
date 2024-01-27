<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_total',
        'total',
        'customer_id',
        'number',
        'date',
        'due_date',
        'discount',
        'reference',
        'terms_and_conditions'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
