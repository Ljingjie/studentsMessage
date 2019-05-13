<?php

namespace App\Http\Controllers;

use App\information;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request){
        $status = Validator::make($request->all(),[
            'number' => 'required|exists:users,number',
            'password' => 'required'
        ]);
        if($request['password']) $request['password'] = md5($request['password']);

        if($status->passes() && $user = User::where($request->all())->first()){
            $user->update(['token'=>md5($request['number'])]);
            return response(['code'=>0,'msg'=>'登录成功','data'=>[
                'number' => $request['number'],
                'username' => $user->username,
                'token' => md5($request['number']),
                'type' => $user->type
            ]]);
        }
        return response(['code'=>422,'msg'=>'账号或密码错误'],422);
    }

    public function register(Request $request){
        $status = Validator::make($request->all(),[
            'number' => 'required|unique:users,number',
            'username' => 'required',
            'password' => 'required',
            'major' => 'required|exists:major,major'
        ]);

        if($status->passes()){
            $request['password'] = md5($request['password']);
            $request['type'] = 'TEACHER';
            User::create($request->except('token'));
            return response(['code'=>0,'msg'=>'success']);
        }
        return response(['code'=>422,'msg'=>'账号已存在或专业不存在'],422);
    }

    public function logout(Request $request){
        $user = User::where('token',$request['token'])->first();
        if(isset($user)){
            $user->update(['token'=>'']);
            return response(['code'=>0,'msg'=>'success']);
        }
    }

    public function delete(Request $request, $id){
        $user = User::where(['id'=>$id,'type'=>'TEACHER'])->first();
        if(isset($user)){
            $user->delete();
            return response(['code'=>0,'msg'=>"success"]);

        }
    }

    public function detail(Request $request){
        $status = Validator::make($request->all(),[
            'number' => 'required|unique:information,number',
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
            $file = $request->file('photo');
            $file_path = "";
            if($file->getMimeType() == 'image/jpeg' || $file->getMimeType() == 'image/jpg' || $file->getMimeType() == 'image/png'){
                $file_path = $file->store('photo');
            }
            $request['photo_url'] = $file_path;
            information::create($request->except(['token','photo']));
            User::create(['number'=>$request['number'],'username'=>$request['name'],'type'=>"STUDENT",'major'=>$request['major'],'password'=>md5('123456')]);
            return response(['code'=>0,'msg'=>'success']);
        }
        return response(['code'=>422,'msg'=>'信息不完整','data'=>$status->errors()],422);
    }

    public function teacher(Request $request){
        $data=  User::where('type','TEACHER')->get();
        return response(['code'=>0,'msg'=>'success','data'=>$data]);
    }

    public function password(Request $request){
        $status = Validator::make($request->all(),[
            'number' => 'required|exists:users,number',
            'password' => 'required|min:6'
        ]);
        if($status->passes() && $user = User::where('number',$request['number'])->first()){
            $request['password'] = md5($request['password']);
            $user->update(['password'=>$request['password']]);
            return response(['code'=>0,'msg'=>'success']);
        }
        return response(['code'=>422,'msg'=>'最短六位数'],422);
    }

}
