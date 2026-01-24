<?php

namespace App\Lib\Commands;

use App\Lib\Database\DatabaseConnexion;
use App\Lib\Database\Dsn;

class DropDatabase extends AbstractCommand
{
    public function execute(): void
    {
        $dsn = new Dsn();
        $dbName = $dsn->getDbName();

        $dsn->addHostToDsn()
            ->addPortToDsn();

        $pdoDsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=postgres',
            $dsn->getHost(),
            $dsn->getPort()
        );

        $pdo = new \PDO($pdoDsn, $dsn->getUser(), $dsn->getPassword());
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $pdo->exec("
            SELECT pg_terminate_backend(pg_stat_activity.pid)
            FROM pg_stat_activity
            WHERE pg_stat_activity.datname = '{$dbName}'
            AND pid <> pg_backend_pid();
        ");

        $pdo->exec("DROP DATABASE IF EXISTS {$dbName};");

        echo "Base de données '{$dbName}' supprimée avec succès.\n";
    }

    public function undo(): void {}

    public function redo(): void {}
}
