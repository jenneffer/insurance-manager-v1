@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Policy Payment List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Expense">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Id</th>
                        <th>Policy No</th>
                        <th>Paid Amount (RM)</th>                        
                        <th>Payment Date</th>
                        <th>Payment Mode</th>
                        <th>Remark</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payment as $key => $data)
                        <tr data-entry-id="{{ $data->id }}">
                            <td></td>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->policy_no }}</td>
                            <td>{{ number_format($data->paid_amount,2) }}</td>    
                            <td>{{ $data->payment_date }}</td>   
                            @if( $data->payment_mode == 'payment_company')<td>Payment by Company</td>@endif   
                            @if( $data->payment_mode == 'cash_individual')<td>Individual Cash</td>@endif   
                            <td>{{ $data->remark}}</td>                           
                            <td>
                                @can('payment_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.payments.show', ['id'=>$data->insurance_id,'ins_details_id'=>$data->insurance_details_id]) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('payment_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.payments.edit', $data->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('payment_delete')
                                    <form action="{{ route('admin.payments.destroy', $data->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

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
@can('payment_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.payments.massDestroy') }}",
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
  $('.datatable-Expense:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection