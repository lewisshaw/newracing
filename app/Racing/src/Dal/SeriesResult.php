<?php
namespace Racing\Dal;

use Doctrine\DBAL\Connection;

class SeriesResult
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function deleteResults(int $seriesId)
    {
        $this->conn->executeUpdate('DELETE FROM Racing.SeriesResult where seriesId = :seriesId', [':seriesId' => $seriesId]);
    }

    public function addResult(
        $seriesId,
        $raceId,
        $helmId,
        $sailNumber,
        $position
    ) {
        $this->conn->executeUpdate(
            'INSERT INTO Racing.SeriesResult (seriesId, raceId, competitorId, sailNumber, position)
            VALUES (:seriesId, :raceId, :competitorId, :sailNumber, :position)',
            [
                ':seriesId' => $seriesId,
                ':raceId' => $raceId,
                ':competitorId' => $helmId,
                ':sailNumber' => $sailNumber,
                ':position' => $position,
            ],
            [
                ':seriesId' => \PDO::PARAM_INT,
                ':raceId' => \PDO::PARAM_INT,
                ':competitorId' => \PDO::PARAM_INT,
                ':sailNumber' => \PDO::PARAM_INT,
                ':position' => \PDO::PARAM_INT, 
            ]
        );
    }
}
