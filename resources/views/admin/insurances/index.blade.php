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
                        <th width="10%">Policy Holder</th>
                        <th width="15%">Location</th>
                        <th width="25%">Policy No.</th>                        
                        <th width="15%">Sum Insured (RM)</th>
                        <th width="20%">Properties Insured</th>                       
                        <th>Modify</th>
                    </tr>                    
                </thead>
                <tbody>
                    @foreach($insurances as $key => $ins) 
                    <tr data-entry-id="{{ $ins->id }}">
                        <td></td>
                        <td>{{$ins->agent->agentDesc}}</td>
                        <td>{{$ins->company->compCode ?? '' }}</td>
                        <td>{{$ins->ins_correspond_address}}</td>                        
                        <td>
                          @foreach($ins->insurance_details as $key => $ins_details)
                            {{$ins_details->policy_no}}<br>
                            <span class="small text-primary">({{$ins_details->date_start}} to {{$ins_details->date_end}})</span>
                            <br>
                          @endforeach
                        </td>                       
                        <td class="text-right">
                          @foreach($ins->insurance_details as $key => $ins_details)
                            {{number_format($ins_details->sum_insured,2)}}<br>
                          @endforeach
                        </td>
                        
                        <td>{{$ins->risk->risk_description ?? ''}}</td>                     
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
                            @can('insurance_renew')
                                <a class="btn btn-xs btn-warning renew_button" href="{{route('admin.insurances.renew', $ins->id) }}">
                                    {{ trans('global.renew') }}
                                </a>     
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
            <h4 class="modal-title">Additional Insured</h4>
        </div>
        <div class="modal-body">
          <table class='table table-sm' id='perils_table'>
            <thead>
                <tr>
                    <th width="8%">Item No.</th>
                    <th width="50%" class="text-center">Description</th>
                    <th width="10%" class="text-right">Rate (%)</th>      
                    <th width="20%" class="text-right">Sum Insured(RM)</th>                                
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

  //show or hide button renew


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
            var rate = (element.rate == null || element.rate == "-")  ? "-" : element.rate+"%";
                tbody +="<tr><td>"+element.ref_no+"</td><td>"+element.description+"</td><td class='text-right'>"+rate+"</td><td class='text-right'>RM "+element.sum_insured.toFixed(2)+"</td></tr>"
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