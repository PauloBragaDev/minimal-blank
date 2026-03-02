<?php
   namespace Source\Domain\Auth;

   use Source\Domain\User\User;
   use Source\Support\ServiceResponse;

    /**
     * Yelloweb | Class LoginUserService
     *
     * @author Paulo Braga <tecnologia@yelloweb.com.br>
     * @package Source\Domain\Auth
     */
    class LoginUserService
    {
        private User $userModel;

        public function __construct()
        {
            $this->userModel = new User();
        }

        public function attempt(string $email, string $password): ServiceResponse
        {
            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                return ServiceResponse::fail("Usuário não encontrado.");
            }

            if (!password_verify($password, $user->password)) {
                return ServiceResponse::fail("Senha inválida.");
            }

            return ServiceResponse::success("Login realizado com sucesso.", $user);
        }
    }