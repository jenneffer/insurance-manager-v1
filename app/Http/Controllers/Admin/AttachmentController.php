<?php

namespace App\Http\Controllers\Admin;

use App\Attachment;
use App\Insurance;
use App\Agent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\MassDestroyAttachmentRequest;
use Gate;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttachmentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('attachment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $attachment = Attachment::all();
        $attachment = DB::table('attachments')
            ->join('insurances', 'insurances.id', '=', 'attachments.ins_id')
            ->join('agents', 'agents.id', '=', 'insurances.ins_agent')
            ->select('attachments.*', 'agents.agentDesc', 'insurances.ins_policy_no')->whereNull('attachments.deleted_at')->get();

        return view('admin.attachments.index', compact('attachment'));
    }

    public function create()
    {
        abort_if(Gate::denies('attachment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $insurance_policy = Insurance::all()->pluck('ins_policy_no', 'id')->prepend(trans('global.pleaseSelect'), '');        
    
        return view('admin.attachments.create', compact('insurance_policy'));
    }

    public function store(StoreAttachmentRequest $request)
    {
        $data = $request->all();
        $policy_id = $data['policy_id']; 
        $request->validate([
            // 'myFile.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            'myFile.*' => 'required|file|max:2048'
        ]);
        if($request->hasFile('myFile')) {    
        $imageNameArr = [];        
            foreach ($request->myFile as $file) {
                // you can also use the original name
                $imageName = time().'-'.$file->getClientOriginalName();
                $imageNameArr[] = $imageName;
                // Upload file to public path in images directory
                $path = $file->storeAs('public/images', $imageName);
                // Database operation

                $attachFile = array(
                    'ins_id' => $policy_id,
                    'file_path' => $imageName,                      
                );
                $attachment = Attachment::create($attachFile);     
            }            
        }

        return redirect()->route('admin.attachments.index');
    }


    // public function destroy(Attachment $attachment)
    // {
    //     abort_if(Gate::denies('attachment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $attachment->delete();

    //     return back();
    // }

    public function massDestroy(MassDestroyAttachmentRequest $request)
    {
        Attachment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
