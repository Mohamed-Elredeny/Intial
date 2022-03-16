<?php

namespace App\Http\Middleware;

use App\Http\Traits\GeneralTrait;
use App\Models\Admin;
use App\Models\Employee;
use App\User;
use Closure;
use Illuminate\Http\Request;

class auth_api
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        $type = $request->header('type');
        if($type == 'user') {
            $exist = User::where('remember_token', $token)->get();
        }elseif($type == 'employee'){
            $exist = Employee::where('remember_token', $token)->get();
        }elseif($type == 'admin'){
            $exist = Admin::where('remember_token', $token)->get();
        }
        if (count($exist) > 0) {
            foreach ($exist as $ex) {
                if ($ex->status == 0) {
                    return $this->returnError(200,'active your account');
                    //2 please active your account
                }
            }
            $this->user = $exist[0];
            return $next($request);
        } else {
            return $this->returnError(200,'unauthenticated user');

        }
    }

}
