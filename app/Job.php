<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Job extends Model
{
    use SoftDeletes;

    public $table = 'job';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'job_name',
        'job_pay_method',        
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public static function getJobName($job_id){
        $result = Job::find($job_id);
        return $result->job_name;
    }
}
