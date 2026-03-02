<?php
    namespace Source\Http\Middlewares;

    use CoffeeCode\Router\Router;
    use Source\Infrastructure\Session\Session;

    /**
     * Yelloweb | Class SecureMiddleware
     *
     * @author Paulo Braga <tecnologia@yelloweb.com.br>
     * @package Source\Http\Middlewares
     */
    class SecureMiddleware
    {
        public function handle(Router $router): bool
        {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {

                if (!csrf_verify($router->data())) {
                    $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                    echo json_encode($json);
                    return false;
                }
            }
            return true;
        }
    }