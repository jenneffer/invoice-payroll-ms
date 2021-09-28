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
<div class="card">
    <div class="card-header">
        Payroll List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Payroll">
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
                    @foreach($payroll as $key => $p)
                        <tr data-entry-id="{{ $p->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $p->id ?? '' }}
                            </td>
                            <td>
                                {{ $p->created_at ?? '' }}
                            </td>
                            <td>
                                {{ $p->emp_name ?? '' }}
                            </td>
                            <td>
                                {{ $p->emp_email ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ number_format($p->total_salary,2) ?? '' }}
                            </td> 
                            <td class="text-right">
                                {{ number_format($p->allowance,2) ?? '' }}
                            </td>  
                            <td class="text-right">
                                {{ number_format($p->deduction,2) ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ number_format($p->emp_tax,2) ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ number_format((($p->total_salary + $p->allowance) - ($p->deduction+$p->emp_tax)),2) ?? '' }}
                            </td>                           
                            <td>
                                <!-- @can('student_show') -->
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.payrolls.show', $p->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                <!-- @endcan -->

                                <!-- @can('student_edit') -->
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.payrolls.edit', $p->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                <!-- @endcan -->

                                <!-- @can('student_delete') -->
                                    <form action="{{ route('admin.payrolls.destroy', $p->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                <!-- @endcan -->

                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.payrolls.print',$p->id) }}" target="_blank" title="Print">
                                    <i class="fas fa-print fa-2x"></i>
                                </a>
                            </td>

                        </tr>
                    @endforeach
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
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 3, 'asc' ]],
    pageLength: 100,
  });
  $('.datatable-Payroll:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection