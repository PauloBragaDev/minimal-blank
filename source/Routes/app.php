<?php 
    /** 
     * 
     * APP ROUTES 
     * 
     **/
    $route->namespace("Source\Http\Controllers\App");
    $route->group("/app", middleware: \Source\Http\Middlewares\AuthAppMiddleware::class);

    $route->get("/", "App:dashboard");
    $route->get("/logoff", "App:logoff");

  
    $route->group(null); 