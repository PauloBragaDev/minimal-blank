<?php
    namespace Source\Bootstrap;

    use CoffeeCode\Router\Router;

    /**
     * Yelloweb | Class App
     *
     * @author Paulo Braga <tecnologia@yelloweb.com.br>
     * @package Source\Bootstrap
     */
    class App
    {
        private Router $router;

        public function __construct()
        {
            session_start();

            $this->router = new Router(url(), ":");
            $this->loadRoutes();
        }

        private function loadRoutes(): void
        {
            $route = $this->router;
            
            require __DIR__ . "/../Routes/web.php";
            require __DIR__ . "/../Routes/app.php";
        }

        public function run(): void
        {
            $this->router->dispatch();

            if ($this->router->error()) {
                $this->router->redirect("/woops/{$this->router->error()}");
            }
        }
    }