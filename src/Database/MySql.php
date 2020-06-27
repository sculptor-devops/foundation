<?php namespace Sculptor\Foundation\Database;

use Exception;
use Illuminate\Support\Facades\DB;
use Sculptor\Foundation\Contracts\Database;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
class MySql implements Database
{
    /**
     * @var string
     */
    private $error = 'Unknown error';

    /**
     * @param string $name
     * @return bool
     * @throws Exception
     */
    public function db(string $name): bool
    {
        try {
            $this->statement("CREATE DATABASE IF NOT EXISTS {$name};", 'Error creating database');

            return true;

        } catch(Exception $e) {
            $this->error = $e->getMessage();

            return false;
        }
    }

    /**
     * @param string $user
     * @param string $password
     * @param string $db
     * @param string $host
     * @return bool
     */
    public function user(string $user, string $password, string $db, string $host = 'localhost'): bool
    {
        try {
            $this->statement("DROP USER IF EXISTS {$user}@'{$host}'", 'Drop user error');

            $this->statement("CREATE USER {$user}@'{$host}' IDENTIFIED BY '{$password}'", 'Error creating user');

            $this->statement("GRANT ALL PRIVILEGES ON {$db}.* TO '{$user}'@'{$host}';", 'Error granting privileges');

            $this->statement("FLUSH PRIVILEGES;", 'Error flushing privileges');

            return true;

        } catch (Exception $e) {
            $this->error = $e->getMessage();

            return false;
        }
    }

    /**
     * @param string $user
     * @param string $password
     * @param string $db
     * @param string $host
     * @return bool
     */
    public function password(string $user, string $password, string $db, string $host = 'localhost'): bool
    {
        try {
            $this->user($user, $password, $db, $host);

            return true;

        } catch (Exception $e) {
            $this->error = $e->getMessage();

            return false;
        }
    }

    /**
     * @return string
     */
    public function error(): string
    {
        return $this->error;
    }

    /**
     * @param string $query
     * @param string $error
     * @throws Exception
     */
    private function statement(string $query, string $error): void
    {
        $result = DB::statement($query);

        if (!$result) {
            throw new Exception($error);
        }
    }
}
