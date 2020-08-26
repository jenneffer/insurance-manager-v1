@extends('layouts.admin')
@section('content')
@can('insurance_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.insurances.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.insurance.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.insurance.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-insurance">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Insurance Agent</th>
                        <th>Policy Holder</th>
                        <th>Policy No.</th>
                        <th>Period of Insurance</th>
                        <th>Issuing Date</th>
                        <th>Issuing Branch</th>
                        <th>Modify</th>
                    </tr>                    
                </thead>
                <tbody>
                    @foreach($insurances as $key => $ins)
                    <tr data-entry-id="{{ $ins->ins_id }}">
                        <td></td>
                        <td>{{$ins->ins_agent}}</td>
                        <td>{{$ins->ins_company}}</td>
                        <td>{{$ins->ins_policy_no}}</td>
                        <td>{{$ins->ins_date_start." - ".$ins->ins_date_end}}</td>
                        <td>{{$ins->ins_issuing_date}}</td>
                        <td>{{$ins->ins_issuing_branch}}</td>
                        <td>
                            @can('insurance_show')
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.insurances.show', $ins->ins_id) }}">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan

                            @can('insurance_edit')
                                <a class="btn btn-xs btn-info" href="{{ route('admin.insurances.edit', $ins->ins_id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                            @endcan

                            @can('insurance_delete')
                                <form action="{{ route('admin.insurances.destroy', $ins->ins_id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('insurance_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.insurances.massDestroy') }}",
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
  $('.datatable-insurance:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection