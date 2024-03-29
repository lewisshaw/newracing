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
                bc.boatClassId, bc.name, pn.pyNumber, bc.persons
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
                bc.boatClassId, bc.name, pn.pyNumber, pn.pyNumberId, bc.persons
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
                boatClassId, name, persons
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

    public function getByName($boatClass)
    {
        $query = '
            SELECT
                bc.boatClassId, bc.name, pn.pyNumber, bc.persons
            FROM
                Racing.BoatClass AS bc
            INNER JOIN
                Racing.PyNumber AS pn
            ON
                bc.boatClassId = pn.boatClassId
            WHERE
                pn.active = 1
            AND
                UPPER(bc.name) = UPPER(:boatClassName)';

        return $this->dbConn->fetchAssoc(
            $query,
            [
                ':boatClassName' => $boatClass,
            ]
        );
    }

    public function insert($name, $persons)
    {
        $query = '
            INSERT INTO Racing.BoatClass(
                name,
                persons
            ) VALUES (
                :name,
                :persons
            )';
        $this->dbConn->executeQuery(
            $query,
            [
                ':name' => $name,
                ':persons' => $persons,
            ],
            [
                ':name' => \PDO::PARAM_STR,
                ':persons' => \PDO::PARAM_INT,
            ]
        );

        return $this->dbConn->lastInsertId();
    }

    public function update($boatClassId, $name)
    {
        $query = '
            UPDATE
                Racing.BoatClass
            SET
                name = :name
            WHERE
                boatClassId = :boatClassId';

        return $this->dbConn->executeQuery(
            $query,
            [
                ':name'        => $name,
                ':boatClassId' => $boatClassId,
            ],
            [
                ':name'        => \PDO::PARAM_STR,
                ':boatClassId' => \PDO::PARAM_INT,
            ]
        );
    }
}
