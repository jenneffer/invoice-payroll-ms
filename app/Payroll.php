<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * Class Student
 * @package App
 */
class Payroll extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    public $table = 'payroll';

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'emp_id',
        'total_salary',
        'deduction',
        'emp_tax',
        'remark',
        'allowance',
        'email_sent',     
        'payroll_date',   
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');

    }

    public function payroll_details()
    {
        return $this->hasMany(PayrollDetails::class, 'payroll_id','id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class,'id', 'emp_id');
    }

    public function scopeBetween($query, Carbon $from, Carbon $to)
    {
        $query->whereBetween('created_at', [$from, $to]);
    }

}
