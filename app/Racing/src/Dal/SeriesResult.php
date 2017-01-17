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

    public function getResults(int $seriesId)
    {
        $query = '
            SELECT sr.seriesId, sr.raceId, sr.competitorId, sr.sailNumber, sr.position, c.firstName, c.lastName
            FROM Racing.SeriesResult as sr
            INNER JOIN Racing.Competitor as c on sr.competitorId = c.competitorId
            WHERE sr.seriesId = :seriesId';

        $results = $this->conn->fetchAll($query, [':seriesId' => $seriesId]);

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[$result['competitorId']][$result['raceId']] = $result;
        }

        return $formattedResults;
    }
}
