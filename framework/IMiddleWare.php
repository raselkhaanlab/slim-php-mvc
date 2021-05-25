<?php
 namespace RKO;
 interface IMiddleWare {
    public function handle(Request $req, Response $res,callable $next);
  }