<?php

namespace App\Domain\User\Repository;

use PDO;

/**
 * Repository.
 */
final class SubmissionCreatorRepository
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
     * Insert user row.
     *
     * @param array $user The user
     *
     * @return int The new ID
     */
    public function insertUser(array $user): int
    {
        $row = [
            'ktp' => $user['ktp'],
            // 'first_name' => $user['first_name'],
            // 'last_name' => $user['last_name'],
            // 'email' => $user['email'],
        ];

        $sql = "INSERT INTO tblreqloan SET 
                ktp=:ktp;";
                // -- first_name=:first_name, 
                // -- last_name=:last_name, 
                // -- email=:email;";

        $this->connection->prepare($sql)->execute($row);

        return (int)$this->connection->lastInsertId();
    }
}