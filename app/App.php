<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class App
{
    public static $request;
    public static $BASE_DIR = __DIR__ . "/..";
    public static $PUBLIC_DIR = __DIR__ . "/../public";

    /*
     * @var \Twig_Environment $twig
     */
    public static $twig;

    public static $urls = [
        '/' => 'IndexController@index',
        '/task/(\d+)/edit' => 'TaskController@edit',
        '/task/create' => 'TaskController@create'
    ];

    public static function bootstrap()
    {
        $loader = new \Twig_Loader_Filesystem(self::$BASE_DIR.'/templates');
        $twig = new \Twig_Environment($loader, array('debug' => true));
        $twig->addExtension(new \Twig_Extension_Debug());
        self::$twig = $twig;

        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'beejee',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => ''
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        date_default_timezone_set('UTC');
    }

    public static function run($request)
    {
        self::$request = $request;

        $request_url = $request['url'];
        foreach (self::$urls as $pattern => $action) {
            $matches = [];
            preg_match('/^'. str_replace( '/', '\/',  $pattern) . '$/', $request_url, $matches);
            if($matches) {
                $controllerClass = 'Controller\\' . explode('@', $action)[0];
                $method = explode('@', $action)[1];
                $controllerInstance = new $controllerClass();
                return call_user_func_array([$controllerInstance, $method], array_slice($matches, 1));
            }
        }
        return
            [
                'code' => 404,
                'body' => "<h1>404 Not found</h1><p>Requested url not found</p>"
            ];
    }
}