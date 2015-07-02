<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class BoatClass
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
                bc.boatClassId, bc.name, pn.pyNumber
            FROM
                Racing.BoatClass AS bc
            LEFT JOIN
                Racing.PyNumber AS pn
            ON
                pn.boatClassId = bc.boatClassId
            AND
                pn.active = 1';

        return $this->dbConn->fetchAll($query);
    }

    public function getAllWithPy()
    {
        $query = '
            SELECT
                bc.boatClassId, bc.name, pn.pyNumber, pn.pyNumberId
            FROM
                Racing.BoatClass AS bc
            INNER JOIN
                Racing.PyNumber AS pn
            ON
                bc.boatClassId = pn.boatClassId
            WHERE
                pn.active = 1
            ORDER BY
                name ASC';

        return $this->dbConn->fetchAll($query);
    }

    public function get($boatClassId)
    {
        $query = '
            SELECT
                boatClassId, name
            FROM
                Racing.BoatClass
            WHERE
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

    public function insert($name)
    {
        $query = '
            INSERT INTO Racing.BoatClass(
                name
            ) VALUES (
                :name
            )';
        return $this->dbConn->executeQuery(
            $query,
            [
                ':name' => $name,
            ],
            [
                ':name' => \PDO::PARAM_STR,
            ]
        );
    }
}
