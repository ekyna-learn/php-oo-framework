<?php

namespace Test;

use Exception;
use PDO;

/**
 * Class Database
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class Database
{
    /** @var PDO */
    private static $connection;

    public static function getConnection(): PDO
    {
        if (self::$connection) {
            return self::$connection;
        }

        $user = $password = null;
        if (1 === $_ENV['DB_MEMORY']) {
            $dsn = 'sqlite::memory:';
            self::$connection = new PDO('sqlite::memory:');
        } else {
            $dsn = sprintf('mysql:host=%s;port=%s', $_ENV['DB_HOST'], $_ENV['DB_PORT']);
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PWD'];
        }

        self::$connection = new PDO($dsn, $user, $password);
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        self::createDatabase();

        return self::$connection;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public static function loadDataset(string $name): void
    {
        if (in_array($name, ['sqlite-schema', 'mysql-schema'], true)) {
            throw new Exception("Schema is already loaded.");
        }

        self::loadFile(__DIR__ . "/data/$name.php");
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private static function createDatabase(): void
    {
        if (1 === $_ENV['DB_MEMORY']) {
            self::loadFile(__DIR__ . '/data/sqlite-schema.php');
        } else {
            self::loadFile(__DIR__ . '/data/mysql-schema.php');
        }
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private static function loadFile(string $path): void
    {
        if (!(is_file($path) && is_readable($path))) {
            throw new Exception("File $path does not exist or is not readable.");
        }

        $statements = require $path;

        if (empty($statements)) {
            throw new Exception("File $path did not return any statement.");
        }

        $conn = self::getConnection();

        foreach ($statements as $statement) {
            if (false === $conn->query($statement)) {
                throw new Exception("Failed to execute query:\n$statement");
            }
        }
    }
}
