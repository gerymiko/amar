<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Model\UserModel;
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
    public function insertUser(UserModel $user): int
    {
        $row = [
            'ktp' => $user['ktp'],
            'jml_pinjaman' => $user['jml_pinjaman'],
            'jangka_waktu' => $user['jangka_waktu'],
            'nama_lengkap' => $user['nama_lengkap'],
            'jk' => $user['jk'],
            'tgl_lahir' => $user['tgl_lahir'],
            'alamat' => $user['alamat'],
            'telepon' => $user['telepon'],
            'email' => $user['email'],
            'kebangsaan' => $user['kebangsaan'],
            'provinsi' => $user['provinsi'],
        ];

        $sql = "INSERT INTO tblreqloan SET 
                ktp=:ktp,
                jml_pinjaman=:jml_pinjaman, 
                jangka_waktu=:jangka_waktu,
                nama_lengkap=:nama_lengkap, 
                jk=:jk,
                tgl_lahir=:tgl_lahir,
                alamat=:alamat,
                telepon=:telepon,   
                email=:email,
                kebangsaan=:kebangsaan,
                provinsi=:provinsi;";

        $this->connection->prepare($sql)->execute($row);

        return (int)$this->connection->lastInsertId();
    }
}