@extends('layouts.admin')
@section('content')
{{-- @can('student_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.payrolls.create") }}">
                Create TimeSheet
            </a>
        </div>
    </div>
@endcan --}}
@php
$now = \Carbon\Carbon::now();
$weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
$weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
@endphp
<div class="card">
    <div class="card-header">
        Payslip List
    </div>

    <div class="card-body">
        <form action="" id="filtersForm">
        @csrf
            <div class="row input-daterange">                                
                <div class="col-sm-3">    
                    <div class="form-group">
                        <label>Date From</label>
                        <input type="text" name="date_from" id="date_from" value="{{ $weekStartDate }}" class="form-control date">                        
                    </div>
                </div>
                <div class="col-sm-3">    
                    <div class="form-group">
                        <label>Date To</label>
                        <input type="text" name="date_to" id="date_to" value="{{ $weekEndDate }}" class="form-control date">                        
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <button type="button" id="search_payroll" class="btn btn-primary" style="bottom:0;position: absolute;">Filter</button>                         
                </div>
            </div>
        </form>  
        <br>
        <div class="table-responsive">                                               
            <table id="payroll_table" class="table table-bordered table-striped table-hover datatable datatable-Payroll">            
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Payroll Date
                        </th>
                        <th>
                            Employee Name
                        </th>
                        <th>
                            Employee Email
                        </th>
                        <th class="text-right">
                            Total Salary ($)
                        </th>  
                        <th class="text-center">
                            Employment<br>Status
                        </th> 
                        <th class="text-center">
                            Pay Period<br>Start
                        </th>
                        <th class="text-center">
                            Pay Period<br>End
                        </th>                                                 
                        <th>
                            Print<br>Payslip
                        </th> 
                        <th>
                            Email<br>Payslip
                        </th>                       
                    </tr>                                           
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">&nbsp;</th>
                        <th class="text-right">Total($)</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent

<script>
$(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    @can('student_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.payrolls.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }
                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }

                    }).done(function () { 
                        location.reload()
                    })
                }
            }
        }
        dtButtons.push(deleteButton)
    @endcan

    $.extend(true, $.fn.dataTable.defaults, {
        order: [[ 3, 'asc' ]],
        pageLength: 100,
    });
    var table = $('.datatable-Payroll:not(.ajaxTable)').DataTable({ 
        // buttons: dtButtons,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        searching: true, // Set false to Remove default Search Control
        ajax: {
            url:"{{ route('admin.payrolls.daterange') }}",
            data: function(data){
                // Read values
                var from_date = $('#date_from').val();
                var to_date = $('#date_to').val();

                // Append to data
                data.searchByFromdate = from_date;
                data.searchByTodate = to_date;
                data._token = "{{ csrf_token() }}";
            }
        },
        columns: [
            {
                data:'DT_RowIndex',
            },
            {
                data:'id',
            },
            {
                data:'payroll_date',                
            },
            {
                data:'emp_name',                
            },
            {
                data:'emp_email',                
            },
            {
                data:'total_salary',                             
            },      
            {
                data:'emp_status',                             
            },      
            {
                data:'start_date',                               
            },
            {
                data:'end_date',                                
            },                        
            {
                data:'print',
            },  
            {
                data:'email',
                render: function(data, type, row, meta){
                    console.log(type)
                    if(type === 'display'){
                        if(row.email_sent == 1){
                            return '<i class="fa fa-check-circle fa-2x" aria-hidden="true" style="color:green;"></i>';
                        }else{
                            return '<a class="btn btn-xs btn-danger" data-id="'+row.id+'" id="send_email"><span style="color:white;">Send Email</span></a>';
                        }
                    }else{
                        return "";
                    }
                    
                }          
            },            
        ],  
        columnDefs: [
            {
                targets:[0],               
                visible:false
            },
            {
                targets: 5,
                className: "text-right",
            },
            {
                targets: [2,6,9,10],
                className: "text-center",
            }
            
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            var total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Total over this page
            var pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                '$'+ currencyFormat(total) +' '
            );
        }
  
    });
    //send email
    $(document).on('click' , '#send_email', function(){
        var id = $(this).data("id");             
        var token = $("meta[name='csrf-token']").attr("content");     
        console.log(id);   
        $.ajax(
        {
            url: '{{route("admin.payrolls.sendmail")}}',
            type: 'POST',
            data: {
                "id": id,                
                "_token": token,
            },
            success: function (data){
                alert(data.msg);
                location.reload();
            }
        });
    
    });
    // set on change date picker
    $('#search_payroll').on('click', function() {
        var from = $("#date_from").val();
        var to = $("#date_to").val();
        if(from && to) {
            table.draw();
        }
    });
    
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });    


});
function currencyFormat(num) {
  return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}
</script>
@endsection