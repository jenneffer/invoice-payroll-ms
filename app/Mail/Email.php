<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;
class Email extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $demo;
    public function __construct($demo)
    {

        $this->demo = $demo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $payroll = $this->demo->payroll;
        $employee = $this->demo->employee;
        $payroll_details = $this->demo->payroll_details;
        $p_date = [];  
        $job = [];           
        foreach($payroll_details as $pd){
            $p_date[] = $pd->date;            
            $job[$pd->job->id][] = array(
                'job_name' => $pd->job->job_name,
                'description' => $pd->job->job_pay_method,
                'tot_hrs' => number_format($pd->total_hours,2),
                'tot_bin' => $pd->total_bin,
                'rate' => $pd->rate 
            );
        }
        $job_details = [];
        foreach ($job as $key => $value) {                        
            foreach ($value as $val) {
                if(isset($job_details[$key]['tot_hrs'])){
                    $job_details[$key]['tot_hrs'] += $val['tot_hrs'];
                }else{
                    $job_details[$key]['tot_hrs'] = $val['tot_hrs'];
                }

                if(isset($job_details[$key]['tot_bin'])){
                    $job_details[$key]['tot_bin'] += $val['tot_bin'];
                }else{
                    $job_details[$key]['tot_bin'] = $val['tot_bin'];
                }

                $job_details[$key]['job_name'] = $val['job_name'];
                $job_details[$key]['description'] = $val['description'];
                $job_details[$key]['rate'] = $val['rate'];
            }
        }
        
        usort($p_date, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });  
        
        $min_date = $p_date[0];
        $max_date = $p_date[count($p_date) - 1];
        $total_salary = $payroll->total_salary;
        
        $pdf = PDF::loadView('mails.payslip', compact('payroll','payroll_details','employee','min_date','max_date','job_details'));
        $mail = $this->to($this->demo->receiver)
                    ->from($this->demo->sender)
                    ->subject('Payslip For '.date('d F Y', strtotime($min_date)).' To '.date('d F Y', strtotime($max_date))) 
                    ->view('mails.email_template')
                    ->attachData($pdf->output(), $employee->emp_name. '_'.$min_date.'_'.$max_date.'.pdf');
        return $mail;
    }
}
