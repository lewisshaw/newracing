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
                seriesId,
                seriesName,
                DATE_FORMAT(startDate, \'%D %M %Y\') AS startDate,
                DATE_FORMAT(endDate, \'%D %M %Y\') AS endDate
            FROM
                Racing.Series
            ORDER BY
                startDate DESC, seriesName';

        return $this->dbConn->fetchAll($query);
    }

    public function get($seriesId)
    {
        $query = '
            SELECT
                seriesId, seriesName, startDate, endDate
            FROM
                Racing.Series
            WHERE
                seriesId = :seriesId';

        return $this->dbConn->fetch(
            $query,
            [
                ':seriesId' => $ser,
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
}
