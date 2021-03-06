<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bill extends Model
{
    //
    protected $table = "bills";
    public function bill_detail(){
    	return $this->hasMany('App\billdetail','id_bill','id');
    }
    public function customer(){
    	return $this->belongsTo('App\customer','id_user','id');
    }

    public function address(){
    	return $this->belongsTo('App\address','address_id','id');
    }
}
