<?php
namespace App\Middleware;
use RKO\IMiddleWare;
class Example  implements IMiddleWare{
    public function handle($req,$res,$next){
        //DO SOME CHECK VALIDATION OR OTHER MIDDLE PROCESS BEFORE ENTER INTO CONTROLLER
        //IN CASE OF ENTER INTO CONTROLLER PLEASE RETURN $next() METHOD CALL;
        // OR JUST RETURN YOUR RESPONSE WITHOUT $next() METHOD CALL;
        return $next();
    }
}