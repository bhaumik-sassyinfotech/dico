<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPayment extends Model
{
    //
    use softDeletes;
    protected $table = 'company_payment';
    protected $fillable = [];
    
}
