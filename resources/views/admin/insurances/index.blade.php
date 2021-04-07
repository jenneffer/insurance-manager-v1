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
<!-- @can('insurance_renew') -->
<!-- Appear only if there's renewal has not been done-->
<!-- <div class="col-sm-12">
    <div class="row floating_warning">
        <i class="fas fa-bell fa-4x" style="color:white;"></i>
        &nbsp;&nbsp;&nbsp;
        <p style="font-size:18px;color:white;"><b>
            REQUIRE ATTENTION<br>FOR INSURANCE OR POLICY RENEWAL
        </b></p>
    </div>        
</div>
@endcan -->
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
                        <th>ID</th>
                        <th>Agent</th>
                        <th>Policy Holder/Owner</th>
                        <th>Insurance Company</th>
                        <th>Class of Insurance</th>    
                        <th>Location/Properties Insured</th>
                        <th>Policy No.</th>   
                        <th>Insurance Period</th>                      
                        <th>Sum Insured (RM)</th>   
                        <th>Remark</th>       
                        <th>Policy Status</th>      
                        <th>Payment Status</th>                                    
                        <th>Modify</th>
                    </tr>                    
                </thead>
                <tbody>
                @if(!empty($insurances))
                    @foreach($insurances as $key => $ins) 
                    <tr data-entry-id="{{ $ins->id }}">
                        <td></td>
                        <td>{{$ins->id}}</td>
                        <td>{{$ins->insurances->agent->agentDesc ?? ''}}</td>
                        <td>{{$ins->insurances->company->compCode ?? '' }}</td>
                        <td>{{$ins->insurances->insurance_company->ins_agent_desc ?? ''}}</td>
                        <td>{{$ins->insurances->ins_class ?? '' }}</td>
                        <td>{{$ins->insurances->ins_correspond_address ?? ''}}</td>                        
                        <td>{{$ins->policy_no ?? ''}}</td>  
                        <td>{{date('d-m-Y', strtotime($ins->date_start))}} to {{date('d-m-Y', strtotime($ins->date_end))}}</td>                      
                        <td class="text-right">{{number_format($ins->sum_insured,2)}}</td>                        
                        <td>{{$ins->remark}}</td>                                                                 
                        @if($ins->policy_status == 'active')<td>Active</td>@endif       
                        @if($ins->policy_status == 'on_hold')<td>On Hold</td>@endif         
                        @if($ins->policy_status == 'cancelled')<td>Cancelled</td>@endif   
                        @if($ins->policy_status == 'inactive')<td>Inactive</td>@endif 
                        @if($ins->payment_status == 'pending')<td style="text-align:center;color:red;">Pending</td>@endif        
                        @if($ins->payment_status == 'paid')<td style="text-align:center;color:green;">Paid</td>@endif                   
                        <td>
                            @can('insurance_show')
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.insurances.show', $ins->insurances->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan

                            @can('insurance_edit')
                                <a class="btn btn-xs btn-info" href="{{ route('admin.insurances.edit', $ins->insurances->id) }}">
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
                            <!-- check the date start and date end of the policy, show button if the date end within 30-50 days-->
                                @php
                                $mostRecent = 0;
                                @endphp
                                @php
                                    $curDate = strtotime($ins->date_end)
                                    @endphp
                                    @if($curDate > $mostRecent)
                                        @php
                                        $mostRecent = $curDate;
                                        @endphp
                                    @endif
                                @php
                                $today = strtotime(date('Y-m-d'));
                                $dateDiff = $mostRecent - $today;
                                $days = round($dateDiff / (60 * 60 * 24));
                                @endphp
                                @if($days <=50)
                                <a class="btn btn-xs btn-warning renew_button" href="{{route('admin.insurances.renew', ['id'=>$ins->insurances->id,'ins_details_id'=>$ins->id]) }}">
                                <i class="fas fa-exclamation-triangle warning_button" style="color:red;"></i> {{ trans('global.renew') }} 
                                </a>  
                                @endif 
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                @endif
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