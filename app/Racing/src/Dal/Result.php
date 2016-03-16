<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class Result
{
    private $dbConn;

    public function __construct(Connection $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function insert($raceId, $sailNumber)
    {
        $query = '
            INSERT INTO
                Racing.Result (raceId, sailNumber)
            VALUES
                (:raceId, :sailNumber)';

        $this->dbConn->executeQuery(
            $query,
            [
                ':raceId'     => $raceId,
                ':sailNumber' => $sailNumber,
            ],
            [
                ':raceId'     => \PDO::PARAM_INT,
                ':sailNumber' => \PDO::PARAM_INT,
            ]
        );

        return $this->dbConn->lastInsertId();
    }

    public function delete($resultId)
    {
        $query = '
            DELETE FROM
                Racing.Result
            WHERE
                resultId = :resultId';
        $this->dbConn->executeQuery(
            $query,
            [':resultId' => $resultId]
        );
    }
}
