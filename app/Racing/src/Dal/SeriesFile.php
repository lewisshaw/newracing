<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class SeriesFile
{
    private $dbConn;

    public function __construct(Connection $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function get($seriesId)
    {
        $query = '
        SELECT
            seriesId, leagueFileName
        FROM
            Racing.SeriesFile
        WHERE
            seriesId = :seriesId';
        return $this->dbConn->fetchAssoc(
            $query,
            [
                ':seriesId' => $seriesId,
            ]
        );
    }

    public function insert($seriesId, $filename)
    {
        $query = '
            INSERT INTO
                Racing.SeriesFile (seriesId, leagueFileName)
            VALUES
                (:seriesId, :filename)';

        $this->dbConn->executeQuery(
            $query,
            [
                ':seriesId' => $seriesId,
                ':filename' => $filename,
            ],
            [
                ':seriesId'     => \PDO::PARAM_INT,
                ':filename' => \PDO::PARAM_STR,
            ]
        );
    }

    public function update($seriesId, $filename)
    {
        $query = '
        UPDATE
            Racing.SeriesFile
        SET
            leagueFileName = :filename
        WHERE
            seriesId = :seriesId';

        $this->dbConn->executeQuery(
            $query,
            [
                ':seriesId' => $seriesId,
                ':filename' => $filename,
            ],
            [
                ':seriesId'     => \PDO::PARAM_INT,
                ':filename' => \PDO::PARAM_STR,
            ]
        );
    }
}
