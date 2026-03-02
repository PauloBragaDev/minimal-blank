<?php 
    namespace Source\Http\Request\Auth;

    use Source\Support\ServiceResponse;

    /**
     * Yelloweb | Class LoginRequest
     *
     * @author Paulo Braga <tecnologia@yelloweb.com.br>
     * @package Source\Http\Request\Auth
     */
    class LoginRequest 
    {
        public function validate(array $data): ServiceResponse
        {
            if (empty($data['email']) || empty($data['password'])) {
                return ServiceResponse::fail("Email e senha são obrigatórios.");
            }

            if (!is_email($data['email'])) {
                return ServiceResponse::fail("Email inválido.");
            }

            return ServiceResponse::success();
        }
    }