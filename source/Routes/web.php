<?php 
    /** 
     * 
     * WEB ROUTES 
     * 
     **/
    $route->namespace("Source\Http\Controllers\Web");

    $route->get("/register", "Web:init");
    $route->post("/register", "Web:register");   

    // auth User
    $route->get("/", "Web:loginPage");
    $route->post("/auth", "Web:auth", middleware: \Source\Http\Middlewares\SecureMiddleware::class);

    $route->group(null); 