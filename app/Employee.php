<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Employee extends Model
{
    use SoftDeletes;

    public $table = 'employee';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'emp_name',
        'emp_doj',
        'emp_email',
        'emp_status'
    ];
    public function payroll()
    {
        return $this->belongsTo(Payroll::class,'emp_id','id');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
