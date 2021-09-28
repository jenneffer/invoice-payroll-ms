@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.companies.create") }}">
            Add Company
        </a>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Company List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Expense">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Address</th>     
                        <th>ABN</th>  
                        <th>ACN</th>  
                        <th>Contact Person</th>  
                        <th>Contact No</th>  
                        <th>Email</th>  
                        <th>Last Invoice No</th>                     
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($company as $key => $comp)
                        <tr data-entry-id="{{ $comp->id }}">
                            <td></td>
                            <td>{{ $comp->id }}</td>
                            <td>{{ $comp->comp_name }}</td>
                            <td>{{ $comp->comp_address }}</td>   
                            <td>{{ $comp->abn_no }}</td>
                            <td>{{ $comp->acn_no }}</td>   
                            <td>{{ $comp->contact_person }}</td>
                            <td>{{ $comp->contact_no }}</td>   
                            <td>{{ $comp->email }}</td>
                            <td>{{ $comp->last_invoice_no }}</td>                            
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.companies.show', $comp->id) }}">
                                    {{ trans('global.view') }}
                                </a>

                                <a class="btn btn-xs btn-info" href="{{ route('admin.companies.edit', $comp->id) }}">
                                    {{ trans('global.edit') }}
                                </a>

                                <form action="{{ route('admin.companies.destroy', $comp->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>

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
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.companies.massDestroy') }}",
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

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-Expense:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection