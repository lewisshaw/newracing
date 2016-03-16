<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class ClassResult
{
    private $dbConn;
    private $resultCompetitor;
    private $result;

    public function __construct(Connection $dbConn, ResultCompetitor $resultCompetitor, Result $result)
    {
        $this->dbConn = $dbConn;
        $this->resultCompetitor = $resultCompetitor;
        $this->result = $result;
    }

    public function getByRace($raceId)
    {
        $query = '
            SELECT
                r.resultId,
                r.raceId,
                r.sailNumber,
                cr.boatClassId,
                cr.position,
                bc.name AS boatClassName
            FROM
                Racing.Result AS r
            INNER JOIN
                Racing.ClassResult AS cr
            ON
                r.resultId = cr.resultId
            INNER JOIN
                Racing.BoatClass AS bc
            ON
                cr.boatClassId = bc.boatClassId
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

    public function delete($resultId) {
        $query = '
            DELETE FROM
                Racing.ClassResult
            WHERE
                resultId = :resultId';
        $this->dbConn->executeQuery(
            $query,
            [':resultId' => $resultId]
        );

        $this->resultCompetitor->deleteByResult($resultId);

        $this->result->delete($resultId);
    }

    public function insert($raceId, $sailNumber, $boatClassId, $position, array $competitors)
    {
        $resultId = $this->result->insert($raceId, $sailNumber);

        $this->addClassResult($resultId, $boatClassId, $position);
        $this->resultCompetitor->addCompetitor($resultId, $competitors['helm'], 'HELM');

        if (isset($competitors['crew'])) {
            $this->resultCompetitor->addCompetitor($resultId, $competitors['crew'], 'CREW');
        }

        return $resultId;
    }

    private function addClassResult($resultId, $boatClassId, $position)
    {
        $query = '
            INSERT INTO
                Racing.ClassResult (resultId, boatClassId, position)
            VALUES
                (:resultId, :boatClassId, :position)';

        $this->dbConn->executeQuery(
            $query,
            [
                ':resultId'    => $resultId,
                ':boatClassId' => $boatClassId,
                ':position'    => $position,
            ],
            [
                ':resultId'    => \PDO::PARAM_INT,
                ':boatClassId' => \PDO::PARAM_INT,
                ':position'    => \PDO::PARAM_INT,
            ]
        );
    }
}
