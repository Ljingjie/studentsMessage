<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class checkToken
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
        if( !$user = User::where('token',$request['token'])->first() ) return response(['code'=>401,'msg'=>'请登录后再试'],422);
        return $next($request);
    }
}
