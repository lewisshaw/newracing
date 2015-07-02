<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class CompetitorType
{
    private $dbConn;

    public function __construct(Connection $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getHandleById($id)
    {
        $query = '
        SELECT
            competitorTypeHandle
        FROM
            Racing.competitorType
        WHERE
            competitorTypeId = :competitorTypeId';

        $handle = $this->dbConn->fetchColumn(
            $query,
            [
                ':competitorTypeId' => $id,
            ],
            0,
            [
                ':competitorTypeId' => \PDO::PARAM_INT,
            ]
        );

        return $handle;
    }

    public function getIdByHandle($handle)
    {
        $query = '
        SELECT
            competitorTypeId
        FROM
            Racing.CompetitorType
        WHERE
            competitorTypeHandle = :competitorTypeHandle';

        $id = $this->dbConn->fetchColumn(
            $query,
            [
                ':competitorTypeHandle' => $handle,
            ],
            0,
            [
                ':competitorTypeHandle' => \PDO::PARAM_STR,
            ]
        );

        if (empty($id)) {
            throw new \Exception('Unable to get Id for handle ' . $handle);
        }

        return $id;
    }
}
