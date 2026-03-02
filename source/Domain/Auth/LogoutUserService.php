<?php
   namespace Source\Domain\Auth;

   use Source\Support\ServiceResponse;
   use Source\Infrastructure\Session\Session;

    /**
     * Yelloweb | Class LogoutUserService
     *
     * @author Paulo Braga <tecnologia@yelloweb.com.br>
     * @package Source\Domain\Auth
     */
    class LogoutUserService 
    {
        public function handle(): ServiceResponse
        {
            (new Session())->unset("authUser");

            return ServiceResponse::success("Logout realizado com sucesso.");
        }
    }