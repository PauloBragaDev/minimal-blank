<?php 
    namespace Source\Domain\User;

    use Source\Domain\User\User;
    use Source\Support\ServiceResponse;

    class RegisterUserService
    {
        public function handle(array $data): ServiceResponse
        {
            $u = new User();
            if ($user = $u->findByEmail($data['email'])) {
                return ServiceResponse::fail("E-mail já cadastrado, faça login.", $data['email']);
            }

            $u->bootstrap(
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['phone']
            );

            if (!$u->save()) {
                return ServiceResponse::fail("Erro ao cadastrar usuário: " . $u->message()->render());
            }
            return ServiceResponse::success("Usuário cadastrado com sucesso.", $u->data());
        }
    }