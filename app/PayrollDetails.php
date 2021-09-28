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
class PayrollDetails extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    public $table = 'payroll_details';

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
        'payroll_id',
        'job_id',
        'total_hours',
        'total_bin',
        'date',
        'rate',
        'total',
        'time_start',
        'time_end',
        'time_rest',
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

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'invoice_id');

    }

}
