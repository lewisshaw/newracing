<?php
namespace Racing\Dal;

use Doctrine\Dbal\Connection;

class UnfinishedResultType
{
    private $dbConn;

    public function __construct(Connection $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getIdByHandle($handle)
    {
        $query = '
        SELECT
            unfinishedTypeId
        FROM
            Racing.UnfinishedResultType
        WHERE
            unfinishedTypeHandle = :unfinishedResultTypeHandle';

        $id = $this->dbConn->fetchColumn(
            $query,
            [
                ':unfinishedResultTypeHandle' => $handle,
            ],
            0,
            [
                ':unfinishedResultTypeHandle' => \PDO::PARAM_STR,
            ]
        );

        if (empty($id)) {
            throw new \Exception('Unable to get Id for handle ' . $handle);
        }

        return $id;
    }
}
