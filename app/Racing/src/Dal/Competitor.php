<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class Competitor
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
                competitorId, firstName, lastName
            FROM
                Racing.Competitor
            ORDER BY
                firstName, lastName';

        return $this->dbConn->fetchAll($query);
    }

    public function get($competitorId)
    {
        $query = '
            SELECT
                competitorId, firstName, lastName
            FROM
                Racing.Competitor
            WHERE
                competitorId = :competitorId';

        return $this->dbConn->fetchAssoc(
            $query,
            [
                ':competitorId' => $competitorId,
            ],
            [
                ':competitorId' => \PDO::PARAM_INT,
            ]
        );
    }

    public function getByName($firstName, $lastName)
    {
        $query = '
            SELECT
                competitorId
            FROM
                Competitor
            WHERE
                UPPER(firstName) = UPPER(:firstName)
            AND
                UPPER(lastName) = UPPER(:lastName)';

        return $this->dbConn->fetchColumn(
            $query,
            [':firstName' => $firstName, ':lastName' => $lastName],
            0
        );
    }

    public function insert($firstName, $lastName)
    {
        $query = '
            INSERT INTO Racing.Competitor (
                firstName,
                lastName
            ) VALUES (
                :firstName,
                :lastName
            )';
        $this->dbConn->executeQuery(
            $query,
            [
                ':firstName' => $firstName,
                ':lastName'  => $lastName,
            ],
            [
                ':firstName' => \PDO::PARAM_STR,
                ':lastName'  => \PDO::PARAM_STR,
            ]
        );

        return $this->dbConn->lastInsertId();
    }

    public function update($competitorId, $firstName, $lastName)
    {
        $query = '
            UPDATE
                Racing.Competitor
            SET
                firstName = :firstName,
                lastName = :lastName
            WHERE
                competitorId = :competitorId';

        return $this->dbConn->executeQuery(
            $query,
            [
                ':firstName'    => $firstName,
                ':lastName'     => $lastName,
                ':competitorId' => $competitorId,
            ],
            [
                ':firstName'     => \PDO::PARAM_STR,
                ':lastName'      => \PDO::PARAM_STR,
                ':competitorId'  => \PDO::PARAM_INT,
            ]
        );
    }
}
