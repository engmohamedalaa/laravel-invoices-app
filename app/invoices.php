<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class invoices extends Model
{
    //
    use SoftDeletes;
    protected $guarded    = [];
    protected $deleted_at = ['deleted_at'];
    public function section()
    {
        return $this->belongsTo('App\sections');
    }
}
