<?php

namespace Source\Infrastructure\Database;

/**
 * Yelloweb | Class DatabaseConnection [ Singleton Pattern ]
 *
 * @author Paulo Braga <tecnologia@yelloweb.com.br>
 * @package Source\Infrastructure\Database
 */
class DatabaseConnection
{
    /** @const array */
    private const OPTIONS = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_CASE               => \PDO::CASE_NATURAL
    ];

    /** @var \PDO */
    private static $instance;

    /**
     * @return \PDO
     */
    public static function getInstance(): ?\PDO
    {
        if (empty(self::$instance)) {
            try {
                
                $host = CONF_DB_HOST;
                if(strpos($_SERVER['HTTP_HOST'], "localhost") !== false) {
                    $host = CONF_DB_HOST_DEV;
                }

                self::$instance = new \PDO(
                    "mysql:host=" . $host . ";dbname=" . CONF_DB_NAME,
                    CONF_DB_USER,
                    CONF_DB_PASS,
                    self::OPTIONS
                );
            } catch (\PDOException $exception) {
                redirect("/woops/problemas");
            }
        }

        return self::$instance;
    }

    /**
     * Connect constructor.
     */
    private function __construct()
    {
    }

    /**
     * Connect clone.
     */
    private function __clone()
    {
    }
}