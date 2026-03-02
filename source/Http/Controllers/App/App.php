<?php
    namespace Source\Http\Controllers\App;

    use Source\Http\Controllers\BaseController;
    use Source\Http\Controllers\Auth\AuthApp;

    use Source\Domain\User\User;
    use Source\Domain\Auth\LogoutUserService;

    /**
     * Class App
     * @package Source\Http\Controllers\App
     */
    class App extends BaseController
    {
        /** @var \Source\Domain\User\User */
        protected User $user;

        public function __construct()
        {   
            parent::__construct(__DIR__."/../../../../themes/" . CONF_VIEW_APP . "/");
            $this->user = AuthApp::user();
        }

        /**
         * LOGOUT
         * 
         * @return void
         */
        public function logoff(): void
        {
            $result = (new LogoutUserService())->handle();

            if (!$result->success) {
                $this->message->error($result->message)->flash();
                redirect("/");
            }

            $this->message->success("Você saiu com sucesso {$this->user->name}.")->flash();
            redirect("/");
        }

        /**
         * DASHBOARD
         */
        public function dashboard(): void
        {
            $head = $this->seo->render(
                "Painel - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/app"),
                theme("/assets/images/share.jpg")
            );
             
            echo $this->view->render("dashboard", [
                "head"       => $head,
                "userOnline" => $this->user
            ]);
        }


    }