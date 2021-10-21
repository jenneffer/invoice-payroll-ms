@extends('layouts.admin')
@section('content')
@can('student_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.payrolls.create") }}">
                Create Payroll
            </a>
        </div>
    </div>
@endcan
@php
$now = \Carbon\Carbon::now();
$weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
$weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
@endphp
<div class="card">
    <div class="card-header">
        Payroll List
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
                        <th width="10" rowspan="2">
                        </th>
                        <th rowspan="2">
                            ID
                        </th>
                        <th rowspan="2">
                            Date
                        </th>
                        <th rowspan="2">
                            Employee Name
                        </th>
                        <th rowspan="2">
                            Employee Email
                        </th>
                        <th class="text-right" rowspan="2">
                            Total Salary ($)
                        </th>  
                        <th class="text-right" rowspan="2">
                            Bonus<br>/Allowance ($)
                        </th> 
                        <th class="text-center" colspan="2">
                            Deduction
                        </th>                         
                        <th class="text-right" rowspan="2">
                            Payable<br>Salary ($)
                        </th>                     
                        <th rowspan="2">
                            Action
                        </th>
                        <th rowspan="2">
                            Print<br>Payslip
                        </th>
                    </tr>                        
                    <tr>
                        <th>Other($)</th>
                        <th>Tax($)</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
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
        serverMethod: 'POST',
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
                render: function( data, type, row){
                    if(type==='display'){
                        return currencyFormat(data);
                    }
                }              
            },
            {
                data:'allowance',                
            },
            {
                data:'deduction',                
            },
            {
                data:'emp_tax',                
            },
            {
                data:'payable_salary', 
                render: function( data, type, row){
                    if(type==='display'){
                        return currencyFormat(data);
                    }
                }           
            },
            {
                data:'action',               
            },
            {
                data:'print',
            },
        ],  
        columnDefs: [
            {
                targets:[0],               
                visible:false
            },
            
        ],
                  
        
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