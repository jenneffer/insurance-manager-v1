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
                        <th style="width: 15%;">Insurance Agent</th>
                        <th>Policy Holder</th>
                        <th>Policy No.</th>
                        <th style="width: 10%;">Period of Insurance</th>
                        <th>Issuing Date</th>
                        <th>Issuing Branch</th>
                        <th>Premium</th>
                        <th style="width: 5%;">Shortcut View</th>
                        <th>Modify</th>
                    </tr>                    
                </thead>
                <tbody>
                    @foreach($insurances as $key => $ins) 
                    <tr data-entry-id="{{ $ins->id }}">
                        <td></td>
                        <td>{{$ins->agent->agentDesc}}</td>
                        <td>{{$ins->company->compCode ?? '' }}</td>
                        <td>{{$ins->ins_policy_no}}</td>
                        <td>{{$ins->ins_date_start}} to <br>{{$ins->ins_date_end}}</td>
                        <td>{{$ins->ins_issuing_date}}</td>
                        <td>{{$ins->ins_issuing_branch}}</td>
                        <td>
                          <b>Gross Premium :</b>RM {{$ins->ins_gross_premium}}
                          <br>
                          <b>Services Tax :</b>RM {{$ins->ins_service_tax}}
                          <br>
                          <b>Stamp Duty :</b>RM {{$ins->ins_stamp_duty}}
                          <br>
                          <b>Total Premium :</b>RM {{$ins->ins_gross_premium + $ins->ins_service_tax + $ins->ins_stamp_duty }}
                        </td>
                        <td>
                          <a href="#" id="{{$ins->id}}" class="sum_insured_modal">Sum Insured</a><br>
                          <a href="#" id="{{$ins->id}}" class="perils_modal">Perils</a>
                        </td>
                        <td>
                            @can('insurance_show')
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.insurances.show', $ins->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan

                            @can('insurance_edit')
                                <a class="btn btn-xs btn-info" href="{{ route('admin.insurances.edit', $ins->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                            @endcan

                            @can('insurance_delete')
                                <form action="{{ route('admin.insurances.destroy', $ins->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
<!-- Modal view interest insured table-->
<div id="InterestInsuredTable" class="modal fade">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Interest Insured</h4>
        </div>
        <div class="modal-body">
            <table class='table table-sm' id='interest_insured_table'>
              <thead>
                  <tr>
                      <th>Item No.</th>
                      <th class="text-center">Interest Description</th>
                      <th class="text-right">Sum Insured (RM)</th>                                
                  </tr>
              </thead>
              <tbody>  
                        
              </tbody>
          </table>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal view perils table-->
<div id="PerilsTable" class="modal fade">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Perils</h4>
        </div>
        <div class="modal-body">
          <table class='table table-sm' id='perils_table'>
            <thead>
                <tr>
                    <th>Code</th>
                    <th class="text-center">Description</th>
                    <th class="text-right">Rate (%)</th>                                
                </tr>
            </thead>
            <tbody>                            
            </tbody>
        </table>
            
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@section('scripts')
@parent
<script>
$(function () {
  //show interest insured data
  $('.sum_insured_modal').click(function(){
    var id = $(this).attr("id");
    $.ajax({
        url:"/admin/interest_insured/retrieve_ii",
        method:"POST",
        data:{
            "_token": "{{ csrf_token() }}",
            id:id
        },
        success:function(data){
            var tbody = ''; 
            $("#interest_insured_table").find("tbody").empty(); //clear all the content from tbody here.                                                             
            $('#InterestInsuredTable').modal('show');
            $( data ).each( function( index, element ){
                tbody +="<tr><td>"+element.ii_item_no+"</td><td>"+element.ii_description+"</td><td class='text-right'>"+element.ii_sum_insured.toLocaleString()+"</td></tr>"
            });
            $('#interest_insured_table').find('tbody').append(tbody);


        }
    });
  });

  $('.perils_modal').click(function(){
      var id = $(this).attr("id");
      $.ajax({
          url:"/admin/perils/retrieve_perils",
          method:"POST",
          data:{
              "_token": "{{ csrf_token() }}",
              id:id
          },
          success:function(data){  
          var tbody = '';  
          $("#perils_table").find("tbody").empty(); //clear all the content from tbody here.       
          $('#PerilsTable').modal('show'); 
          $( data ).each( function( index, element ){
                tbody +="<tr><td>"+element.prls_ref_no+"</td><td>"+element.prls_description+"</td><td class='text-right'>"+element.prls_rate+"%</td></tr>"
            });
            $('#perils_table').find('tbody').append(tbody);
          }
      });
  });
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
              data: { ids: ids, _method: 'DELETE' }
          }).done(function () { location.reload() })
        
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