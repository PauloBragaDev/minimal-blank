<?php 
    namespace Source\Http\Request\Auth;

    use Source\Support\ServiceResponse;

    /**
     * Yelloweb | Class RegisterRequest
     *
     * @author Paulo Braga <tecnologia@yelloweb.com.br>
     * @package Source\Http\Request\Auth
     */
    class RegisterRequest 
    {
        public function validate(array $data): ServiceResponse
        {
            if (empty($data['name']) || 
            empty($data['email']) || 
            empty($data['phone']) || 
            empty($data['password']) || 
            empty($data['password_confirm'])) {
                return ServiceResponse::fail("Preencha todos os campos.");
            }

            if (!is_email($data['email'])) {
                return ServiceResponse::fail("Email inválido.");
            }

            if ($data['password'] !== $data['password_confirm']) {
                return ServiceResponse::fail("As senhas não conferem.");
            }

            return ServiceResponse::success();
        }
    }