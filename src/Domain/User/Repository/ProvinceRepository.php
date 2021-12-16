<?php

namespace App\Domain\User\Repository;

use PDO;

/**
 * Repository.
 */
final class ProvinceRepository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * FindAll province row.
     *
     * @param array $province The province
     *
     * @return array The province
     */
    public function findAll(): array
    {
        $sql = "SELECT id FROM tblprovinsi WHERE id IN (1,2,3,4);";
        $prov = $this->connection->prepare($sql);
        $prov->execute();
        return  $prov->fetchAll();
        // $this->connection->prepare($sql)->execute();

        // return (array)$this->connection->fetch();
    }
}