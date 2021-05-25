<?php 
 return [
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    "app_title"=>env('app_title',"SLIM PHP MVC"),
        /*
    |--------------------------------------------------------------------------
    | sub-root
    |--------------------------------------------------------------------------
    |
    | This value is the needed to set if your application is sit inside a sub directory of a root directory 
    | suppose for xampp your application will need sit inside htdocs or www . htdocs or www is the root directory for xampp apache server
    | so your application directory name will be the sub-root
    | any other location as required by the application or its packages.
    |
    */

    "sub-root"=>env('sub-root',""),
     /*
    |--------------------------------------------------------------------------
    | session name
    |--------------------------------------------------------------------------
    |
    | This value is the needed to set if your application is sit inside a sub directory of a root directory 
    | suppose for xampp your application will need sit inside htdocs or www . htdocs or www is the root directory for xampp apache server
    | so your application directory name will be the sub-root
    | any other location as required by the application or its packages.
    |
    */
    "session-name"=>env("session-name","__rphpXfc")
    
 ];
