<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class ResultCompetitor
{
    private $dbConn;
    private $competitorType;

    public function __construct(Connection $dbConn, CompetitorType $competitorType)
    {
        $this->dbConn = $dbConn;
        $this->competitorType = $competitorType;
    }

    public function addCompetitor($resultId, $competitorId, $competitorTypeHandle)
    {
        $competitorTypeId = $this->competitorType->getIdByHandle($competitorTypeHandle);

        $query = '
        INSERT INTO
            Racing.ResultCompetitor (resultId, competitorId, competitorTypeId)
        VALUES
            (:resultId, :competitorId, :competitorTypeId)';

        $this->dbConn->executeQuery(
            $query,
            [
                ':resultId'         => $resultId,
                ':competitorId'     => $competitorId,
                ':competitorTypeId' => $competitorTypeId
            ],
            [
                ':resultId'         => \PDO::PARAM_INT,
                ':competitorId'     => \PDO::PARAM_INT,
                ':competitorTypeId' => \PDO::PARAM_INT,
            ]
        );
    }

    public function getCompetitorByResult($resultId, $competitorTypeHandle)
    {
        $query = '
            SELECT
                CONCAT(firstName, \' \', lastName)
            FROM
                Racing.ResultCompetitor AS rc
            INNER JOIN
                Racing.CompetitorType AS ct
            ON
                ct.competitorTypeId = rc.competitorTypeId
            INNER JOIN
                Racing.Competitor AS c
            ON
                c.competitorId = rc.competitorId
            WHERE
                resultId = :resultId
            AND
                ct.competitorTypeHandle = :competitorTypeHandle';

        return $this->dbConn->fetchColumn(
            $query,
            [
                ':resultId' => $resultId,
                ':competitorTypeHandle' => $competitorTypeHandle,
            ],
            0,
            [
                ':resultId' => \PDO::PARAM_INT,
                ':competitorTypeHandle' => \PDO::PARAM_STR,
            ]
        );
    }

    public function deleteByResult($resultId)
    {
        $query = '
            DELETE FROM
                Racing.ResultCompetitor
            WHERE
                resultId = :resultId';
        return $this->dbConn->executeQuery(
            $query,
            [':resultId' => $resultId]
        );
    }
}
