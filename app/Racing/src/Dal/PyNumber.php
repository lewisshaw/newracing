<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class PyNumber
{
    private $dbConn;

    public function __construct(Connection $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getInactiveByBoatClassId($boatClassId)
    {
        $query = '
            SELECT
                pyNumberId, pyNumber
            FROM
                Racing.PyNumber
            WHERE
                active = 0
            AND
                boatClassId = :boatClassId
            ORDER BY
                pyNumberId DESC';

        return $this->dbConn->fetchAll(
            $query,
            [
                ':boatClassId' => $boatClassId,
            ],
            [
                ':boatClassId' => \PDO::PARAM_INT,
            ]
        );
    }

    public function getActiveByBoatClassId($boatClassId)
    {
        $query = '
            SELECT
                pyNumberId, pyNumber
            FROM
                Racing.PyNumber
            WHERE
                active = 1
            AND
                boatClassId = :boatClassId';

        return $this->dbConn->fetchAssoc(
            $query,
            [
                ':boatClassId' => $boatClassId,
            ],
            [
                ':boatClassId' => \PDO::PARAM_INT,
            ]
        );
    }

    public function insert($boatClassId, $pyNumber)
    {
        $this->deactivateCurrentPyNumber($boatClassId);

        $query = '
            INSERT INTO Racing.PyNumber (
                boatClassId,
                pyNumber,
                active
            ) VALUES (
                :boatClassId,
                :pyNumber,
                1
            )';
        $this->dbConn->executeQuery(
            $query,
            [
                ':boatClassId' => $boatClassId,
                ':pyNumber'  => $pyNumber,
            ],
            [
                ':boatClassId' => \PDO::PARAM_INT,
                ':pyNumber'  => \PDO::PARAM_INT,
            ]
        );

        return $this->dbConn->lastInsertId();
    }

    private function deactivateCurrentPyNumber($boatClassId)
    {
        $query = '
            UPDATE
                Racing.PyNumber
            SET
                active = 0
            WHERE
                boatClassId = :boatClassId
            AND
                active = 1';

        $this->dbConn->executeQuery(
            $query,
            [
                ':boatClassId' => $boatClassId,
            ],
            [
                'boatClassId' => \PDO::PARAM_INT,
            ]
        );
    }
}
