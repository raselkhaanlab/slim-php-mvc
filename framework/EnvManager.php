<?php
try{
    $appEnv = getenv('APP_ENV')?:getenv('app_env');
    $appEnv= strtolower($appEnv);
    $envPath=ROOT;
    switch($appEnv){
        case "production";
        $envPath .= "/.env.production";
        break;
        case "development";
        $envPath .= "/.env.development";
        break;
        case "test";
        $envPath .= "/.env.test";
        break;
        default:
        if(file_exists($envPath."/.env.local")){
            $envPath .= "/.env.local";
        }
        else{
            $envPath .= "/.env";
        }
        break;
    }
    if(file_exists($envPath)){
        $handle = fopen("$envPath", "r");
        if($handle){
            while (($line = fgets($handle)) !== false) {
                putenv(trim($line));
            }
            fclose($handle);
        }
    }
}
catch(Exception $e){
}
function env($key,$default=''){
    $env=getenv($key);
    return trim($env)?:trim($default);
}