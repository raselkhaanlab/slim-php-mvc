<?php
namespace RKO;

class Router {
    private  $getRoutes = [];
    private  $postRoutes = [];
    private  $putRoutes = [];
    private  $patchRoutes = [];
    private  $deleteRoutes = [];
    private  $fallbacks=[];
    private  $methodNotFounds=[];
    private  $config;
    private  $request;
    private  $response;
    private  $kernel;
    // Hold the class instance.
    private static $instance = null;
  
  // The constructor is private
  // to prevent initiation with outer code.
  private function __construct(){
    $this->config = APP_CONFIG;
    $this->request= new Request();
    $this->response= new Response();
    $this->kernel = KERNEL;
}
  // The object is created from within the class itself
  // only if the class has no instance.
  public static function initialize()
  {
    if (self::$instance == null)
    {
      self::$instance = new self();
    }
    return self::$instance;
  }
 public static function __callStatic($method,$args){
     $instance = self::$instance;
     if(self::$instance ===null){
        $instance = self::getInstance();
     }
     if(method_exists(__CLASS__,$method)){
         call_user_func_array([$instance,$method],$args);
     }
     else{
         throw new \Exception("$method DOES NOT EXISTS ON".self." class");
     }
 }
    private  function fallback($fn){
        $this->fallbacks[] = $fn;
    }
    private function resolveMiddlewareFromKernel(array $middlewares=[]){
        $_r=[];
        foreach($middlewares as $key =>$middleware){
            if(array_key_exists($middleware,$this->kernel)){
                if(class_exists($this->kernel[$middleware])){
                    $_r[]=$this->kernel[$middleware];
                }
                else{
                    throw new \Exception ("sorry! ".($this->kernel[$middleware])." not exists on kernel.php. Please check kernel.php\n");
                }
            }
            elseif(class_exists($middleware)){
                $_r[]=$middleware;
            }
            else{
                throw new \Exception("sorry! middleware $middleware not exists on kernel.php or $middleware class not exists.");
            }
        }
        return $_r;
    }
    private  function methodNotFound($fn){
        $this->methodNotFounds[] = $fn;
    }
    private  function get($url,$fn,$middlewares=[]){
        $url =$this->prepareUrl($url);
        $middlewares = $this->resolveMiddlewareFromKernel($middlewares);
        $fn ['middlewares'] =$middlewares;
        $this->getRoutes[$url]=$fn;
    }
    private  function post($url,$fn,$middlewares=[]){
        $url =$this->prepareUrl($url);
        $middlewares = $this->resolveMiddlewareFromKernel($middlewares);
        $fn ['middlewares'] =$middlewares;
        $this->postRoutes[$url]=$fn;
    }
    private  function patch($url,$fn,$middlewares=[]){
        $url =$this->prepareUrl($url);
        $middlewares = $this->resolveMiddlewareFromKernel($middlewares);
        $fn ['middlewares'] =$middlewares;
        $this->patchRoutes[$url]=$fn;
    }
    private  function put($url,$fn,$middlewares=[]){
        $url =$this->prepareUrl($url);
        $middlewares = $this->resolveMiddlewareFromKernel($middlewares);
        $fn ['middlewares'] =$middlewares;
        $this->putRoutes[$url]=$fn;
    }
    private  function delete($url,$fn,$middlewares=[]){
        $url =$this->prepareUrl($url);
        $middlewares = $this->resolveMiddlewareFromKernel($middlewares);
        $fn ['middlewares'] =$middlewares;
        $this->deleteRoutes[$url]=$fn;
    }
    private function prepareUrl($url){
        $url = trim($url);
        $root = trim($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
        $subRoot = array_key_exists('sub-root',$this->config)?$this->config['sub-root']:'';
        if(!empty($subRoot )){
            $alreadySubRootWithInRoot= strpos($root,$subRoot);
            if(!$alreadySubRootWithInRoot){
                $url = $subRoot.$url;
            }
        }
        $url = "/".$url;
        $url = \str_replace("//","/",$url);
        return $url;
    }
    private function isUriFound($uri){
        $getExists = array_key_exists($uri,$this->getRoutes);
        $postExists = array_key_exists($uri,$this->postRoutes);
        $putExists = array_key_exists($uri,$this->putRoutes);
        $patchExists = array_key_exists($uri,$this->patchRoutes);
        $deleteExists = array_key_exists($uri,$this->deleteRoutes);
        return ($getExists|| $postExists || $putExists || $patchExists || $deleteExists);
    }
    private function  notFound(){
        $method = $this->request->getMethod();
        $uri = $this->request->getRequestUri();
        if($this->isUriFound($uri)&& ($this->request->isXhr() || $this->request->wantJson())){
            http_response_code(405);
            return $this->response->sendJson([
                'message'=>'method not found',
                'method' =>$method,
                'url'=>$uri,
                'status'=>405
            ]);
        }
        elseif($this->isUriFound($uri)&& !($this->request->isXhr() || $this->request->wantJson())){
            \http_response_code(405);
            return "METHOD NOT FOUND";
        }
        elseif(($this->request->isXhr() || $this->request->wantJson())){
            \http_response_code(404);
            return $this->response->sendJson([
                'message'=>'not found',
                'method' =>$method,
                'url'=>$uri,
                'status'=>404
            ]);
        }
        elseif(!($this->request->isXhr() || $this->request->wantJson())){
            \http_response_code(404);
            return "NOT FOUND";
        }
    }
    private function handleNotFound(&$fn){
            $uri = $this->request->getRequestUri();
            if($this->isUriFound($uri) && count($this->methodNotFounds)){
                \http_response_code(405);
                $fn = $this->methodNotFounds[0];
            }
            elseif(count($this->fallbacks)){
                \http_response_code(404);
                $fn = $this->fallbacks[0];
            }
            else{
                $fn = function(){
                    return $this->notFound();
                };
            }
    }
    public function handleMiddleWare($middlewares,$controller,$action,$size,$n=0){
        if($size <=0){
            return $controller->{$action}($this->request,$this->response);
        }
        if($n>= $size){
            return $controller->{$action}($this->request,$this->response);
        }
        $obj = new $middlewares[$n]();
        return $obj->handle($this->request,$this->response,function() use($middlewares,$controller,$action,$size,$n){
            $n++;
            return $this->handleMiddleWare($middlewares,$controller,$action,$size,$n);
        });
    }
    private  function resolve(){
        $method = $this->request->getMethod();
        $uri = $this->request->getRequestUri();
        switch ($method){
            case 'GET':
                $fn = array_key_exists($uri,$this->getRoutes)?$this->getRoutes[$uri]:null;
                break;
            case 'POST':
                $fn = array_key_exists($uri,$this->postRoutes)?$this->postRoutes[$uri]:null;
                break;
            case 'PUT':
                $fn = array_key_exists($uri,$this->putRoutes)?$this->putRoutes[$uri]:null;
                break;
            case 'PATCH':
                $fn = array_key_exists($uri,$this->patchRoutes)?$this->patchRoutes[$uri]:null;
                break;
            case "DELETE":
                $fn = $this->deleteRoutes[$uri]??null;
                break;
        }
        if(empty($fn) || !isset($fn) || \is_null($fn)){
          $this->handleNotFound($fn);
        }
        if(\is_array($fn)){
            $class = array_key_exists(0,$fn)?$fn[0]:'';
            if($class && class_exists($class)){
                $controller = new $class();
            }
            else{
                throw new \Exception("controller '".$class."' NOT EXISTS for '".$uri."' route");
            }
            $action = $fn[1];
            $middlewares = array_key_exists('middlewares',$fn)?$fn['middlewares']:[];
            \ob_start();
            $content = $this->handleMiddleWare($middlewares,$controller,$action,count($middlewares));
            echo $content;
            $output=\ob_get_clean();
            echo $output;

            }
        elseif(\is_callable($fn)){
            \ob_start();
            $content=$fn();
            echo $content;
            $output=\ob_get_clean();
            echo $output;
        }
        else{
            throw new \Exception("please define route correctly for $uri");
        }
    }
}