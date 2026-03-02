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
         * LIST ALL USERS
         * 
         * @param int|null $clientId
         * @param string|null $profile
         * @return null|User
         */
        public function getAll(?int $clientId = null, ?string $profile = null): ?User 
        {
            $columns = "U.*, C.company_name, C.cnpj";

            $join    = " as U
                        INNER JOIN yw_client AS C ON C.id = U.id_client";

            $terms   = "U.active = 1 AND C.active = :active";
            $params  = "active=1";

            if(!empty($clientId)){
                $terms   .= " AND U.id_client = :id_client";
                $params  .= "&id_client={$clientId}";
            }

            if(!empty($profile)){
                $terms   .= " AND U.profile = :profile";
                $params  .= "&profile={$profile}";
            }
        
            return parent::join($terms, $params, $join, $columns);
        }

        public function getAllUsersPerStore(int $storeId, int $clientId): ?array
        {
            $find = $this->select("id_client = :id_client AND active = 1", "id_client={$clientId}")->fetch(true);
            if(!$find){
                $this->message->error("Nenhum usuário encontrado");
                return null;
            }

            $users = [];
            foreach($find as $user){
                $permissions = json_decode($user->permissions, true);
                if(!empty($permissions)){
                    foreach($permissions as $key => $value){
                        if($key == $storeId){
                            $users[] = $user;
                        }
                    }
                }
            }

            return $users;
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

        /**
         * @return string
         */
        public function fullName(): string
        {
            return "{$this->name} {$this->last_name}";
        }

        /**
         * @return string|null
         */
        public function photo(): ?string
        {
            if ($this->photo && file_exists(__DIR__ . "/../../" . CONF_UPLOAD_DIR . "/{$this->photo}")) {
                return $this->photo;
            }

            return null;
        }

        /**
         * UPDATE USER 
         * 
         * @param int $userId
         * @param array $data
         * @param bool $adminScreen
         * @return null|User
         */
        public function updateUser(int $userId, array $data, bool $adminScreen = true): ?User
        {
            $user = $this->findById($userId);

            if (!$user) {
                $this->message->error("Woops! User not found");
                return null;
            }

            if($adminScreen){
                $user->id_client   = $data['id_client'];
                $user->permissions = !empty($data['permissions']) ? json_encode($data['permissions']) : null;
            }

            $user->profile     = $data['profile'];
            $user->name        = $data['name'];
            $user->last_name   = $data['last_name'];
            $user->mail        = $data['mail'];
            $user->phone       = $data['phone'];
            $user->birth       = $data['birth'];
            $user->sex         = $data['sex'];

            if(!empty($data['pwd'])){
                $user->pwd = passwd($data["pwd"]);
            }

            if (!$user->save()) {
                $user->message->error("Woops! There was a problem saving, contact the developer");
                return null;
            }

            return $user;
        }

        /**
         * UPDATE PASSWORD
         * 
         * @param int $userId
         * @param array $data
         * @return bool
         */
        public function updatePassword(int $userId, array $data): bool
        {
            $user = $this->findById($userId);

            if($data['newPassword'] != $data['confirmPassword']){
                $this->message->error("Woops! A nova senha deve ser confirmada.");
                return false;
            }

            $user->pwd = passwd($data["newPassword"]);

            if (!$user->save()) {
                $user->message->error("Woops! There was a problem saving, contact the developer");
                return false;
            }

            return true;
        }

        /**
         * INSERT NEW USER 
         * 
         * @param array $data
         * @return null|User
         */
        public function addUser(array $data): ?User 
        {
            if(!empty($data['edit'])){
                $user = $this->updateUser($data['edit'], $data);
                return $user;
            }

            if($this->findByEmail(mb_strtolower($data["mail"]), "id")){
                $this->message->warning("O E-mail informado já possui cadastro.");
                return null;
            }

            $userId = $this->bootstrap(
                $data['id_client'],
                $data['profile'],
                !empty($data['permissions']) ? json_encode($data['permissions']) : null,
                $data['name'],
                $data['last_name'],
                $data['mail'],
                $data['phone'],
                $data['birth'],
                $data['sex'],
                gen_uuid(),
                $data['invite'] ?? null)->save(true);
            
            if(empty($userId)){
                $this->message->warning("Woooops! Houve um erro ao salvar. Tente novamente mais tarde!");
                return null;
            }

            return $this->findById($userId);
        }

        /** 
         * VALIDATE HASH AND MAIL
         * 
         * @param string $hash
         * @param string $mail
         * @return bool
         */
        public function validateHashAndMail(string $hash, string $mail): bool
        {
            if(!$this->select("link_hash = :hash AND mail = :mail", "hash={$hash}&mail={$mail}")->fetch()){
                $this->message->error("Woops! Hash ou e-mail inválido.");
                return false;
            }

            return true;
        }
    }