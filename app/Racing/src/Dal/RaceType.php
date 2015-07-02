<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class RaceType
{
    private $dbConn;

    public function __construct(Connection $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getHandleById($id)
    {
        $query = '
        SELECT
            raceTypeHandle
        FROM
            Racing.RaceType
        WHERE
            raceTypeId = :raceTypeId';

        $handle = $this->dbConn->fetchColumn(
            $query,
            [
                ':raceTypeId' => $id,
            ],
            0,
            [
                ':raceTypeId' => \PDO::PARAM_INT,
            ]
        );

        return $handle;
    }

    public function getIdByHandle($handle)
    {
        $query = '
        SELECT
            raceTypeId
        FROM
            Racing.RaceType
        WHERE
            raceTypeHandle = :raceTypeHandle';

        $id = $this->dbConn->fetchColumn(
            $query,
            [
                ':raceTypeHandle' => $handle,
            ],
            0,
            [
                ':raceTypeId' => \PDO::PARAM_STR,
            ]
        );

        if (empty($id)) {
            throw new \Exception('Unable to get Id for handle ' . $handle);
        }

        return $id;
    }
}
