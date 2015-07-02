<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class Race
{
    private $dbConn;
    private $raceType;

    public function __construct(Connection $dbConn, RaceType $raceType)
    {
        $this->dbConn   = $dbConn;
        $this->raceType = $raceType;
    }

    public function getAllBySeries($seriesId)
    {
        $query = '
        SELECT
            r.raceId,
            rt.raceTypeHandle,
            r.seriesId,
            r.name,
            r.laps,
            DATE_FORMAT(r.date, \'%D %M %Y\') AS date
        FROM
            Racing.Race AS r
        INNER JOIN
            Racing.RaceType AS rt
        ON
            rt.raceTypeId = r.raceTypeId
        WHERE
            r.seriesId = :seriesId';

        return $this->dbConn->fetchAll(
            $query,
            [
                ':seriesId' => $seriesId,
            ],
            [
                'seriesId' => \PDO::PARAM_INT,
            ]
        );
    }

    public function get($raceId)
    {
        $query = '
        SELECT
            r.raceId, rt.raceTypeHandle, r.seriesId, r.name, r.laps, r.date
        FROM
            Racing.Race AS r
        INNER JOIN
            Racing.RaceType AS rt
        ON
            rt.raceTypeId = r.raceTypeId
        WHERE
            r.raceId = :raceId';

        return $this->dbConn->fetchAssoc(
            $query,
            [
                ':raceId' => $raceId,
            ],
            [
                ':raceId' => \PDO::PARAM_INT,
            ]
        );
    }

    public function insert($raceType, $seriesId, $name, $laps, $date)
    {
        $raceTypeId = $this->raceType->getIdByHandle($raceType);
        $query = '
            INSERT INTO Racing.Race (
                raceTypeId,
                seriesId,
                name,
                laps,
                date
            ) VALUES (
                :raceTypeId,
                :seriesId,
                :name,
                :laps,
                :date
            )';
        return $this->dbConn->executeQuery(
            $query,
            [
                ':raceTypeId' => $raceTypeId,
                ':seriesId'   => $seriesId,
                ':name'       => $name,
                ':laps'       => $laps,
                ':date'       => $date,
            ],
            [
                ':raceTypeId' => \PDO::PARAM_INT,
                ':seriesId'   => \PDO::PARAM_INT,
                ':name'       => \PDO::PARAM_STR,
                ':laps'       => \PDO::PARAM_INT,
                ':date'       => \PDO::PARAM_STR,
            ]
        );
    }
}
