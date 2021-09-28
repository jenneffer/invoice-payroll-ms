<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Invoice extends Model
{
    use SoftDeletes;

    /**
     * folder to save invoices
     */
    public const FOLDER = 'invoices';

    public $table = 'invoices';

    protected $dates = [
        'date',        
        'paid_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'farm_comp_id',
        'company_id',
        'date',        
        'invoice_number',
        'acc_no',
        'bank',
        'bsb',
        'total_amount',
        'gst',
        'super_amount',
        'sub_total',
        'paid_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');

    }

    public function farm_company()
    {
        return $this->belongsTo(FarmCompany::class, 'farm_comp_id');

    }

    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetails::class, 'invoice_id');
    }

    public function getPeriodFromAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;

    }

    public function getPaidAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;

    }

    public function setPaidAtAttribute($value)
    {
        $this->attributes['paid_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;

    }
}
