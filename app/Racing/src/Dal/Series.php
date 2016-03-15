<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class Series
{
    private $dbConn;

    public function __construct(Connection $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getAll()
    {
        $query = '
            SELECT
                s.seriesId,
                s.seriesName,
                DATE_FORMAT(s.startDate, \'%D %M %Y\') AS startDate,
                DATE_FORMAT(s.endDate, \'%D %M %Y\') AS endDate,
                sf.leagueFileName
            FROM
                Racing.Series AS s
            LEFT JOIN
                Racing.SeriesFile AS sf
            ON
                s.seriesId = sf.seriesId
            ORDER BY
                startDate DESC, seriesName';

        return $this->dbConn->fetchAll($query);
    }

    public function get($seriesId)
    {
        $query = '
            SELECT
                seriesId,
                seriesName,
                DATE_FORMAT(startDate, \'%Y-%m-%d\') AS startDate,
                DATE_FORMAT(endDate, \'%Y-%m-%d\') AS endDate
            FROM
                Racing.Series
            WHERE
                seriesId = :seriesId';

        return $this->dbConn->fetchAssoc(
            $query,
            [
                ':seriesId' => $seriesId,
            ],
            [
                ':seriesId' => \PDO::PARAM_INT,
            ]
        );
    }

    public function insert($name, $startDate, $endDate)
    {
        $query = '
            INSERT INTO Racing.Series (
                seriesName,
                startDate,
                endDate
            ) VALUES (
                :seriesName,
                :startDate,
                :endDate
            )';
        return $this->dbConn->executeQuery(
            $query,
            [
                ':seriesName' => $name,
                ':startDate'  => $startDate,
                ':endDate'    => $endDate,
            ],
            [
                ':seriesName' => \PDO::PARAM_STR,
                ':startDate'  => \PDO::PARAM_STR,
                ':endDate'    => \PDO::PARAM_STR,
            ]
        );
    }

    public function update($seriesId, $name, $startDate, $endDate)
    {
        $query = '
            UPDATE
                Racing.Series
            SET
                seriesName = :seriesName,
                startDate = :startDate,
                endDate = :endDate
            WHERE
                seriesId = :seriesId';
        return $this->dbConn->executeQuery(
            $query,
            [
                ':seriesName' => $name,
                ':startDate'  => $startDate,
                ':endDate'    => $endDate,
                ':seriesId'   => $seriesId,
            ],
            [
                ':seriesName' => \PDO::PARAM_STR,
                ':startDate'  => \PDO::PARAM_STR,
                ':endDate'    => \PDO::PARAM_STR,
                ':seriesId'   => \PDO::PARAM_INT,
            ]
        );
    }

    public function getByDate($date)
    {
        $query = '
            SELECT
                seriesId,
                seriesName,
                DATE_FORMAT(startDate, \'%Y-%m-%d\') AS startDate,
                DATE_FORMAT(endDate, \'%Y-%m-%d\') AS endDate
            FROM
                Racing.Series
            WHERE
                startDate <= :date
            AND
                endDate >= :date';

        return $this->dbConn->fetchAll(
            $query,
            [
                ':date' => $date
            ]
        );
    }

    public function getBeforeDate($date)
    {
        $query = '
            SELECT
                seriesId,
                seriesName,
                DATE_FORMAT(startDate, \'%Y-%m-%d\') AS startDate,
                DATE_FORMAT(endDate, \'%Y-%m-%d\') AS endDate
            FROM
                Racing.Series
            WHERE
                endDate < :date
            ORDER BY
                endDate desc';

        return $this->dbConn->fetchAll(
            $query,
            [
                ':date' => $date
            ]
        );
    }

}
