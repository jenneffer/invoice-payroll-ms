@extends('layouts.admin')
@section('content')
@can('invoice_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.invoices.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.invoice.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.invoice.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Invoice">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Farm Company
                        </th>
                        <th>
                            Invoice Date
                        </th>
                        <th>
                            Invoice No.
                        </th>
                        <th>
                            Account No.
                        </th>
                        <th>
                            Sub Total(AUD)
                        </th>
                        <th>
                            GST(10%)
                        </th>
                        <th>
                            Total Amount(AUD)
                        </th>
                        <th>
                            Paid Date
                        </th>
                        <th>
                            Action
                        </th>
                        <th>
                            Print<br>Invoice
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $key => $invoice)
                        <tr data-entry-id="{{ $invoice->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $invoice->id ?? '' }}
                            </td>
                            <td>
                                {{ $invoice->farm_company->comp_name ?? '' }}
                            </td>
                            <td class="text-center">
                                {{ date('d-m-Y', strtotime($invoice->date)) ?? '' }}
                            </td>
                            <td class="text-center">
                                {{ $invoice->invoice_number ?? '' }}
                            </td>
                            <td class="text-center">
                                {{ $invoice->acc_no ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ number_format($invoice->sub_total,2) ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ number_format($invoice->gst,2) ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ number_format($invoice->total_amount,2) ?? '' }}
                            </td>
                            <td>
                                {{ $invoice->paid_at ?? '' }}
                            </td>
                            <td>                                
                                @can('invoice_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.invoices.show', $invoice->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('invoice_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.invoices.edit', $invoice->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('invoice_delete')
                                    <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                                @can('invoice_toggle_paid')
                                    <form action="{{ route('admin.invoices.togglePaid', $invoice->id) }}" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input
                                            type="submit"
                                            class="btn btn-xs {{ ($invoice->paid_at) ? 'btn-warning' : 'btn-success'}}"
                                            value="{{ ($invoice->paid_at) ? 'Mark as unpaid' : 'Mark as paid' }}"
                                        >
                                    </form>
                                @endcan
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.invoices.print',$invoice->id) }}" target="_blank" title="Print">
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
@can('invoice_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.invoices.massDestroy') }}",
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
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-Invoice:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection
