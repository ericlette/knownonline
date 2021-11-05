<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [

        'id',
        'product_id',
        'ref_id',
        'quantity',
        'name'
    ];

    /**
     * @return belongsTo
     */
    public function order()
    {
        return $this->belongsTo(OrderDetail::class);
    }
}
