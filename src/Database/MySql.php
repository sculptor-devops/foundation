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
     * @param string $password
     * @param string $username
     * @param string $host
     * @return $this
     */
    public function set(string $password, string $username = 'root', string $host = '127.0.0.1'): Database
    {
        config([
            'database.connections.sculptor_mysql_manager' => [
                'driver' => 'mysql',
                'database' => 'mysql',
                'host' => $host,
                'username' => $username,
                'password' => $password
            ]
        ]);

        DB::setDefaultConnection('sculptor_mysql_manager');

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function db(string $name): bool
    {
        return DB::statement("CREATE DATABASE IF NOT EXISTS {$name};");
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
            DB::statement("DROP USER IF EXISTS {$user}@'{$host}'");

            $created = DB::statement("CREATE USER {$user}@'{$host}' IDENTIFIED BY '{$password}'");

            if (!$created) {
                $this->error = 'Error creating user';

                return false;
            }

            $grant = DB::statement("GRANT ALL PRIVILEGES ON {$db}.* TO '{$user}'@'{$host}';");

            if (!$grant) {
                $this->error = 'Error granting privileges';

                return false;
            }

            $flush = DB::statement("FLUSH PRIVILEGES;");

            if (!$flush) {
                $this->error = 'Error flushing privileges';

                return false;
            }

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
}
