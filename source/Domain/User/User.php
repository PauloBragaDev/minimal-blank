<?php
    namespace Source\Domain\User;

    use Source\Infrastructure\Database\BaseModel;

    /**
     * Yelloweb | Class User
     *
     * @author Paulo Braga <tecnologia@yelloweb.com.br>
     * @package Source\Domain\User
     */
    class User extends BaseModel
    {
        /**
         * User constructor.
         */
        public function __construct()
        {
            parent::__construct("yw_app_users", ["id"], ["name", "email", "password"]);
        }

        /**
         * @param string $name
         * @param string $email
         * @param string $password
         * @param string|null $phone
         * @param string|null $birth
         * @return User
         */
        public function bootstrap(
            string $name,
            string $email,
            string $password,
            ?string $phone = "",
            ?string $birth = ""
        ): User {
            $this->name      = $name;
            $this->email     = $email;
            $this->password  = $password;
            $this->phone     = $phone;
            $this->birth     = $birth;
            return $this;
        }

        /**
         * @param string $email
         * @param string $columns
         * @return null|User
         */
        public function findByEmail(string $email, string $columns = "*"): ?User
        {
            return $this->select("email = :email", "email={$email}", $columns)->fetch();
        }
    }