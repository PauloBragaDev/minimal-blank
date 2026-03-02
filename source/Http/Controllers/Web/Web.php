<?php
    namespace Source\Http\Controllers\Web;

    use Source\Http\Controllers\BaseController;
    use Source\Infrastructure\Session\Session;

    use Source\Http\Request\Auth\LoginRequest;
    use Source\Http\Request\Auth\RegisterRequest;
    
    use Source\Domain\Auth\LoginUserService;
    use Source\Domain\User\RegisterUserService;

    use Source\Http\Controllers\Auth\AuthApp;

    /**
     * Web Controller
     * @package Source\Http\Controllers\Web
     */
    class Web extends BaseController
    {
        /**
         * Web constructor.
         */
        public function __construct()
        {
            parent::__construct(__DIR__ . "/../../../../themes/" . CONF_VIEW_THEME . "/");
        }

        /**
         * REGISTER PAGE - GET
         * @return void
         */
        public function init(): void
        {
            $head = $this->seo->render(
                "Entrar - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/auth"),
                theme("/assets/images/share.jpg")
            );
            
            echo $this->view->render("register", [
                "head"   => $head
            ]);
        }

        /**
         * REGISTER ACTION - POST 
         * @param array $data
         * @return void
         */
        public function register(array $data): void
        {
            $request = new RegisterRequest();
            $validation = $request->validate($data);
          
            if (!$validation->success) {
                echo json_encode([
                    "message" => $this->message->error($validation->message)->render()
                ]);
                return;
            }

            $service = new RegisterUserService();
            $result = $service->handle($data);
        
            if (!$result->success) {
                if ($result->data) {
                    echo json_encode([
                        "redirect" => url("/"),
                        "message" => $this->message->warning($result->message)->flash()
                    ]);
                    return;
                }
        
                echo json_encode([
                    "message" => $this->message
                        ->error($result->message)->render()
                ]);
                return;
            }

            (new Session())->set("authUser", $result->data->id);
        
            echo json_encode([
                "redirect" => url("/app"),
                "message" => $this->message->success($result->message)->flash()
            ]);
        }

        /**
         * LOGIN PAGE - GET
         * 
         * @return void
         */
        public function loginPage(): void
        {
            if (AuthApp::check()) {
                redirect("/app");
                return;
            }

            $head = $this->seo->render(
                "Entrar - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/auth"),
                theme("/assets/images/share.jpg")
            );
            
            echo $this->view->render("auth-login", [
                "head"   => $head,
                "cookie" => filter_input(INPUT_COOKIE, "authEmail")
            ]);
        }

        /**
         * LOGIN ACTION
         * @param array $data
         */
        public function auth(array $data): void
        {
            $request = new LoginRequest();
            $validation = $request->validate($data);
          
            if (!$validation->success) {
                echo json_encode([
                    "message" => $this->message->error($validation->message)->render()
                ]);
                return;
            }

            $authService = new LoginUserService();
            $response = $authService->attempt(
                $data['email'],
                $data['password']
            );

            if (!$response->success) {
                echo json_encode([
                    "message" => $this->message->error($response->message)->render()
                ]);
                return;
            }

            (new Session())->set("authUser", $response->data->id);

            $json["redirect"] = url("/app");
            $json["message"] = $this->message->success("Login realizado com sucesso")->flash();

            echo json_encode($json);
            return;
        }

    }