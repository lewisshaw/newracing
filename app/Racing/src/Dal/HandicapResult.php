<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class HandicapResult
{
    private $dbConn;
    private $resultCompetitor;

    public function __construct(
        Connection $dbConn,
        ResultCompetitor $resultCompetitor
    ) {
        $this->dbConn = $dbConn;
        $this->resultCompetitor = $resultCompetitor;
    }

    public function getByRace($raceId)
    {
        $query = '
            SELECT
                r.resultId,
                r.raceId,
                r.sailNumber,
                hr.pyNumberId,
                pn.pyNumber,
                hr.time,
                hr.laps,
                bc.name AS boatClassName
            FROM
                Racing.Result AS r
            INNER JOIN
                Racing.HandicapResult AS hr
            ON
                r.resultId = hr.resultId
            INNER JOIN
                Racing.PyNumber AS pn
            ON
                pn.pyNumberId = hr.pyNumberId
            INNER JOIN
                Racing.BoatClass AS bc
            ON
                pn.boatClassId = bc.boatClassId
            WHERE
                raceId = :raceId';

        $results = $this->dbConn->fetchAll(
            $query,
            [
                ':raceId' => $raceId,
            ],
            [
                ':raceId' => \PDO::PARAM_INT,
            ]
        );

        foreach ($results as &$result) {
            $result['helm'] = $this->resultCompetitor->getCompetitorByResult($result['resultId'], 'HELM');
            $result['crew'] = $this->resultCompetitor->getCompetitorByResult($result['resultId'], 'CREW');
            if (!$result['crew']) {
                $result['crew'] = '';
            }
        }

        return $results;
    }

    public function insert($raceId, $sailNumber, $pyNumberId, $time, $laps, array $competitors)
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

        $resultId = $this->dbConn->lastInsertId();

        $this->addHandicapResult($resultId, $pyNumberId, $time, $laps);
        $this->resultCompetitor->addCompetitor($resultId, $competitors['helm'], 'HELM');

        if (isset($competitors['crew'])) {
            $this->resultCompetitor->addCompetitor($resultId, $competitors['crew'], 'CREW');
        }

        return $resultId;
    }

    private function addHandicapResult($resultId, $pyNumberId, $time, $laps)
    {
        $query = '
            INSERT INTO
                Racing.HandicapResult (resultId, pyNumberId, time, laps)
            VALUES
                (:resultId, :pyNumberId, :time, :laps)';

        $this->dbConn->executeQuery(
            $query,
            [
                ':resultId'   => $resultId,
                ':pyNumberId' => $pyNumberId,
                ':time'       => $time,
                ':laps'       => $laps,
            ],
            [
                ':resultId'   => \PDO::PARAM_INT,
                ':pyNumberId' => \PDO::PARAM_INT,
                ':time'       => \PDO::PARAM_INT,
                ':laps'       => \PDO::PARAM_INT,
            ]
        );
    }
}
