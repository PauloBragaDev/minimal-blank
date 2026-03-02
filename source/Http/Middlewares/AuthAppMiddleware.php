<?php
    namespace Source\Http\Middlewares;

    use CoffeeCode\Router\Router;
    use Source\Http\Controllers\Auth\AuthApp;
    
    class AuthAppMiddleware
    {
        public function handle(Router $router): bool
        {
            if (!AuthApp::check()) {
                redirect("/");
                return false;
            }
            
            return true;
        }
    }