<?php
    namespace Source\Support;

    class ServiceResponse
    {
        public function __construct(
            public bool $success,
            public ?string $message = null,
            public mixed $data = null
        ) {}

        public static function success(?string $message = null, mixed $data = null): self
        {
            return new self(true, $message, $data);
        }

        public static function fail(?string $message = null, mixed $data = null): self
        {
            return new self(false, $message, $data);
        }
    }