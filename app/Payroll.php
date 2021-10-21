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
        'allowance',     
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
        return $this->hasMany(PayrollDetails::class, 'payroll_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeBetween($query, Carbon $from, Carbon $to)
    {
        $query->whereBetween('created_at', [$from, $to]);
    }

}
