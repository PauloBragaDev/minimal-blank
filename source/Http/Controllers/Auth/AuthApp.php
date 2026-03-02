<?php 
    namespace Source\Http\Controllers\Auth;

    use Source\Domain\User\User;
    use Source\Infrastructure\Session\Session;

    class AuthApp
    {
        public static function user(): ?User
        {
            $session = new Session();

            if (!$session->has("authUser")) {
                return null;
            }

            return (new User())->findById($session->authUser, "id, name, email, phone");
        }

        public static function check(): bool
        {
            return self::user() !== null;
        }

        public static function id(): ?int
        {
            $session = new Session();
            return $session->authUser ?? null;
        }

        public static function logout(): void
        {
            $session = new Session();
            $session->unset("authUser");
        }
    }