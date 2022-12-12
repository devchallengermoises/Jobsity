<?php

namespace App\Domain\Stock;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockHistory extends Model
{
    use SoftDeletes; // toggle soft deletes
    protected $table = 'stock_history';
    protected $fillable = ['name', 'symbol', 'open', 'high', 'low', 'close', 'user_id'];
    protected $hidden = ['user_id', 'created_at', 'updated_at', 'deleted_at', 'id'];
    protected $dates =  ['date', 'created_at', 'updated_at', 'deleted_at'];

}
