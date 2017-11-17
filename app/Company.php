<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';
    use softDeletes;
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'company_name',
    ];
}
