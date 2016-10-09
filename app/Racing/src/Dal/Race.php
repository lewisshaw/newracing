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
            r.isPublished,
            DATE_FORMAT(r.date, \'%D %M %Y\') AS date
        FROM
            Racing.Race AS r
        INNER JOIN
            Racing.RaceType AS rt
        ON
            rt.raceTypeId = r.raceTypeId
        WHERE
            r.seriesId = :seriesId
        ORDER BY
            r.name DESC';

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

    public function setPublished($raceId, $published)
    {
        $query = '
            UPDATE
                Racing.Race
            SET
                isPublished = :isPublished
            WHERE
                raceId = :raceId';

        return $this->dbConn->executeQuery(
            $query,
            [
                ':isPublished' => $published,
                ':raceId'      => $raceId,
            ],
            [
                ':isPublished' => \PDO::PARAM_BOOL,
                ':raceId'      => \PDO::PARAM_INT,
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

    public function getPublishedBySeries($seriesId)
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
            r.seriesId = :seriesId
        AND
            r.isPublished = 1
        ORDER BY
            r.date DESC, r.name DESC';

        return $this->dbConn->fetchAll(
            $query,
            [
                ':seriesId' => $seriesId,
            ],
            [
                ':seriesId' => \PDO::PARAM_INT,
            ]
        );
    }


}
