<?php

namespace App\Http\Controllers;

use App\information;
use App\score;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class scoreController extends Controller
{
    public function index(Request $request){
        $user = User::where('token',$request['token'])->first();
        $data = [];
        switch ($user->type){
            case 'ADMIN':
                $student = User::where(['type'=>'STUDENT'])->get();
                foreach($student as $item){
                    $data[] = score::where('number',$item->number)->get();
                }
                break;
            case 'TEACHER':
                $student = User::where(['major'=>$user->major,'type'=>'STUDENT'])->get();
                foreach($student as $item){
                    $data[] = score::where('number',$item->number)->get();
                }
                break;
            case 'STUDENT':
                $data = score::where('number',$user->number)->get();
                break;
        }
        return response(['code'=>0,'msg'=>'success','data'=>$data]);
    }

    public function store(Request $request){
        $status = Validator::make($request->all(),[
            'number' => 'required|exists:users,number'
        ]);
        if($status->passes()){
            $student = User::where('number',$request['number'])->first();
            foreach ($request->except(['token','number']) as $key=>$item){
                $user = information::where('number',$request['number'])->first();
                score::create(['number'=>$student->number,'name'=>$user->name,'subject'=>$key,'score'=>$item]);
            }
            return response(['code'=>0,'msg'=>'success']);
        }
        return response(['code'=>422,'msg'=>"请重新输入"],422);
    }
}
