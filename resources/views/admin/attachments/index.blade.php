@extends('layouts.admin')
@section('content')
@can('attachment_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.attachments.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.attachment.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.attachment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Attachment">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Insurance Agent</th>
                        <th>Policy No.</th>
                        <th>Files</th>
                    </tr>
                </thead>
                <tbody>   
                @foreach($attachment as $key => $att)
                <tr data-entry-id="{{ $att->id }}">
                    <td></td>
                    <td>{{ $att->agentDesc }}</td>
                    <td>{{ $att->ins_policy_no }}</td>
                    <td><a href="{{ asset('storage/images/'.$att->file_path) }}" data-fancybox="gallery">{{$att->file_path}}</a></td>
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
    @can('attachment_delete')
      let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
      let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.attachments.massDestroy') }}",
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
  $('.datatable-Attachment:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection