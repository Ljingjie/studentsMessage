<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class checkTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::where('token',$request['token'])->first();
        if($user->type == 'ADMIN' || $user->type == "TEACHER"){ return $next($request); }
        return response(['code'=>401,'msg'=>'权限不足','data'=>$user->type],401);
    }
}
