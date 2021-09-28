<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FarmCompany extends Model
{
    use SoftDeletes;

    public $table = 'farm_company';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'comp_name',
        'comp_address',
        'abn_no',
        'contact_person',
        'comp_address',
        'contact_no',
        'email',
        'super',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    public function insurances()
    {
        return $this->hasMany(Insurance::class, 'ins_company', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'company_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
