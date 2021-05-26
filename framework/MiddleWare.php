<?php
 namespace RKO;
 abstract class MiddleWare {
    public function handle(Request $req, Response $res,callable $next){
      return $next();
    }
  }