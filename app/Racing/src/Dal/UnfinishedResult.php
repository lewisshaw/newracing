<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class UnfinishedResult
{
    private $dbConn;
    private $resultCompetitor;
    private $unfinishedResultType;

    public function __construct(
        Connection $dbConn,
        ResultCompetitor $resultCompetitor,
        UnfinishedResultType $unfinishedResultType
    ) {
        $this->dbConn = $dbConn;
        $this->resultCompetitor = $resultCompetitor;
        $this->unfinishedResultType = $unfinishedResultType;
    }

    public function getByRace($raceId)
    {
        $query = '
            SELECT
                r.resultId,
                r.raceId,
                r.sailNumber,
                ur.boatClassId,
                urt.unfinishedTypeHandle,
                bc.name AS boatClassName
            FROM
                Racing.Result AS r
            INNER JOIN
                Racing.UnfinishedResult AS ur
            ON
                r.resultId = ur.resultId
            INNER JOIN
                Racing.BoatClass AS bc
            ON
                ur.boatClassId = bc.boatClassId
            INNER JOIN
                Racing.UnfinishedResultType AS urt
            ON
                urt.unfinishedTypeId = ur.unfinishedTypeId
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
                Racing.ResultCompetitor
            WHERE
                resultId = :resultId';
        $this->dbConn->executeQuery(
            $query,
            [':resultId' => $resultId]
        );

        $query = '
            DELETE FROM
                Racing.UnfinishedResult
            WHERE
                resultId = :resultId';
        $this->dbConn->executeQuery(
            $query,
            [':resultId' => $resultId]
        );

        $query = '
            DELETE FROM
                Racing.Result
            WHERE
                resultId = :resultId';
        $this->dbConn->executeQuery(
            $query,
            [':resultId' => $resultId]
        );
    }

    public function insert($raceId, $sailNumber, $boatClassId, $unfinishedResultType, array $competitors)
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

        $this->addUnfinishedResult($resultId, $boatClassId, $unfinishedResultType);
        $this->resultCompetitor->addCompetitor($resultId, $competitors['helm'], 'HELM');

        if (isset($competitors['crew'])) {
            $this->resultCompetitor->addCompetitor($resultId, $competitors['crew'], 'CREW');
        }

        return $resultId;
    }

    private function addUnfinishedResult($resultId, $boatClassId, $unfinishedResultType)
    {
        $unfinishedResultTypeId = $this->unfinishedResultType->getIdByHandle($unfinishedResultType);

        $query = '
            INSERT INTO
                Racing.UnfinishedResult (resultId, boatClassId, unfinishedTypeId)
            VALUES
                (:resultId, :boatClassId, :unfinishedResultTypeId)';

        $this->dbConn->executeQuery(
            $query,
            [
                ':resultId'    => $resultId,
                ':boatClassId' => $boatClassId,
                ':unfinishedResultTypeId' => $unfinishedResultTypeId,
            ],
            [
                ':resultId'    => \PDO::PARAM_INT,
                ':boatClassId' => \PDO::PARAM_INT,
                ':unfinishedResultTypeId' => \PDO::PARAM_INT,
            ]
        );
    }
}
