@extends('layouts.admin')
@section('content')
@can('student_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.timesheet.create") }}">
                Create TimeSheet
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
        Time Sheet List
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
                            Date
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
                        <th class="text-right">
                            Bonus<br>/Allowance ($)
                        </th> 
                        <th class="text-center">
                            Deduction<br>Other($)
                        </th> 
                        <th class="text-center">
                            Deduction<br>Tax($)
                        </th>                        
                        <th class="text-center">
                            Payable<br>Salary ($)
                        </th>                     
                        <th>
                            Action
                        </th>
                        <th>
                            Print<br>Payslip
                        </th>
                    </tr>                                            
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: right">Total</th>
                        <th></th>
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
        serverMethod: 'POST',
        searching: true, // Set false to Remove default Search Control
        ajax: {
            url:"{{ route('admin.timesheet.daterange') }}",
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
            },
            {
                data:'action', 
                render : function(data, type, row, meta){
                    // console.log(row.id);
                    return type === 'display' ?
                    '<a class="btn btn-xs btn-primary" data-value="" href="timesheet/'+row.id+'">View</a>&nbsp;' 
                    +'<a class="btn btn-xs btn-info" data-value="" href="timesheet/'+row.id+'/edit">Edit</a>&nbsp;'
                    +'<button id="deleteRecord" class="btn btn-xs btn-danger" data-id="'+row.id+'">Delete</button>' : '';
                }              
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
            {
                targets: [5,9],
                className: "text-right",
            },
            {
                targets: [6,7,8,11],
                className: "text-center",
            }
            
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
             
            var totalSalary = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
            var bonus = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
            
            var deduction_others = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
            var deduction_tax = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

            var payable = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
            
            
            $( api.column( 0 ).footer() ).html('Total');
            $( api.column( 5 ).footer() ).html(currencyFormat(totalSalary));
            $( api.column( 6 ).footer() ).html(currencyFormat(bonus));
            $( api.column( 7 ).footer() ).html(currencyFormat(deduction_others));
            $( api.column( 8 ).footer() ).html(currencyFormat(deduction_tax));
            $( api.column( 9 ).footer() ).html(currencyFormat(payable));
            
        }
                  
        
    });

    // set on change date picker
    $('#search_payroll').on('click', function() {
        var from = $("#date_from").val();
        var to = $("#date_to").val();
        if(from && to) {
            table.draw();
        }
    });
    $(document).on('click' , '#deleteRecord', function(){
        var id = $(this).data("id");       
        var token = $("meta[name='csrf-token']").attr("content");        
        if (confirm('Are you sure you want to delete this?')) {
            $.ajax(
            {
                url: "timesheet/destroy/"+id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data){
                    if(data == 1){
                        alert('Timesheet successfully deleted!');
                        location.reload();                         
                    }                    
                }
            });
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