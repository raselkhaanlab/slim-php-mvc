<?php
namespace App\Middleware;
class Example  extends BaseMiddleWare{
    public function handle($req,$res,$next){
        //DO SOME CHECK VALIDATION OR OTHER MIDDLE PROCESS BEFORE ENTER INTO CONTROLLER
        //IN CASE OF ENTER INTO CONTROLLER PLEASE RETURN $next() METHOD CALL;
        // OR JUST RETURN YOUR RESPONSE WITHOUT $next() METHOD CALL FOR INVALID REQUEST;
        return $next();
    }
}