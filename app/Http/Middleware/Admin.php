<?php

namespace agencia\Http\Middleware;
//necesitamos agregar esta libreria
use Illuminate\Contracts\Auth\Guard;
//necesitamos agregar esta libreria
use Session;
use Closure;

class Admin
{

    protected $auth;
    public function __construct(Guard $auth){

        $this->auth=$auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //el usuario ya esta logueado y hay un auth , nos referemiso a su id y si este es
        //desistinto de 1 que me de un error , si no que me deje pasar
        if($this->auth->user()->id != 1){
            Session::flash('message-error','no tiene privilegios');
            //me redirecciona a la vista index dentro de admin
            return redirect()->to('/admin');  
        }
        //sigue con la peticion
        return $next($request);
    }
}
