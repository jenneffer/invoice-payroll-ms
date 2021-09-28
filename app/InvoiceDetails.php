<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class InvoiceDetails extends Model
{
    use SoftDeletes;

    /**
     * folder to save invoices
     */    

    public $table = 'invoice_details';

    protected $dates = [
        'date',        
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'invoice_id',
        'date',        
        'description',
        'amount_charged',        
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');

    }
}
