<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\information;
use App\User;
use Illuminate\Support\Facades\Validator;

class infoController extends Controller
{
    public function index(Request $request){
        $user = User::where('token',$request['token'])->first();
        $data = [];
        switch ($user->type){
            case 'ADMIN':
                $data = information::all();
                break;
            case 'STUDENT':
                $data = information::where('number',$user->number)->get();
                break;
            case 'TEACHER':
                $data = information::where('major',$user->major)->get();
                break;
        }
        return response(['code'=>0,'msg'=>'success','data'=>$data]);
    }

    public function update(Request $request){
        $status = Validator::make($request->all(),[
            'number' => 'required',
            'name' => 'required',
            'sex' => 'required',
            'birth' => 'required',
            'card' => 'required',
            'contact' => 'required',
            'department' => 'required|exists:department,department',
            'major' => 'required|exists:major,major',
            'place' => 'required',
            'role' => 'required'
        ]);

        if($status->passes()){
            $info = information::where('id',$request['id'])->update($request->except(['token','id','number']));
            return response(['code'=>0,'msg'=>'success']);
        }
        return response(['code'=>422,'msg'=>'部分信息错误，修改失败','error'=>$status->errors(),'data'=>$request->except(['token','id','number'])],422);
    }

    public function destroy($id){
        $information = information::find($id);
        if(isset($information)){
            User::where('number',$information->number)->delete();
            $information->delete();
            return response(['code'=>0,'msg'=>'success']);
        }
        return response(['code'=>404,'msg'=>'not found'],404);
    }
    
    public function search(Request $request){
        $user = User::where('token',$request['token'])->first();
        $data = $user->type == 'ADMIN' ? information::where('number',$request['num'])->first() : information::where(['number'=>$request['num'],'major'=>$user->major])->get();

        if( count($data)) return response(['code'=>0,'msg'=>'success','data'=>$data]);

        $res = $user->type == 'ADMIN' ? information::all() : information::where(['major'=>$user->major])->get();
        return response(['code'=>422,'msg'=>'无搜索结果','data'=>$res]);
    }
}
