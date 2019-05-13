<?php

namespace App\Http\Controllers;

use App\department;
use App\major;
use App\subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class schoolController extends Controller
{
    public function department(Request $request){
        $status = Validator::make($request->all(),[
            'department' => 'required'
        ]);
        if(!$status->passes()) return response(['code'=>422,'msg'=>'请输入有效内容'],422);
        department::firstOrCreate(['department'=>$request['department']]);
        return response(['code'=>0,'msg'=>'success']);
    }

    public function major(Request $request){
        $status = Validator::make($request->all(),[
            'department' => 'required|exists:department',
            'major' => 'required|unique:major'
        ]);

        if($status->passes()){
            $department = department::where('department',$request['department'])->first();
            major::create(['department_id'=>$department['id'],'major'=>$request['major']]);
            return response(['code'=>0,'msg'=>'success']);
        }
        return response(['code'=>422,'msg'=>'院系不存在，或专业已存在'],422);
    }

    public function subject(Request $request){
        $status = Validator::make($request->all(),[
            'major' => 'required|exists:major,major',
            'subject*' => 'required'
        ]);

        if($status->passes()){
            $major = major::where('major',$request['major'])->first();
            foreach ($request['subject'] as $item){
                subject::firstOrCreate(['major_id'=>$major->id,'subject'=>$item]);
            }
            return response(['code'=>0,'msg'=>'success']);
        }
        return response(['code'=>422,'msg'=>'专业不存在'],422);
    }

    public function index(){
        $data = [];
        $major = major::all();
        foreach ($major as $item){
            $department = department::where('id',$item->department_id)->first();
            $item['department'] = $department->department;
            $data[] = $item;
        };
        $department = department::all();
        return response(['code'=>0,'msg'=>'success','data'=>$data,'department'=>$department]);
    }

    public function showSubject(){
        $data = subject::orderBy('major_id','DESC')->get();
        $res = [];
        foreach($data as $item){
            $major = major::where('id',$item->major_id)->first();
            $item['major'] = $major->major;
            $res[] = $item;
        }
        return response(['code'=>0,'msg'=>'success','data'=>$res]);
    }

    public function score(Request $request){

    }
}
