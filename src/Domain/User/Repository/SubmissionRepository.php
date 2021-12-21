<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Model\UserData;
use App\Factory\QueryFactory;
use DomainException;

/**
 * Repository.
 */
final class SubmissionRepository
{
    private QueryFactory $queryFactory;

    /**
     * The constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     */
    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    /**
     * Insert user row.
     *
     * @param UserData $user The user data
     *
     * @return int The new ID
     */
    public function insertUser(UserData $user): int
    {
        return (int)$this->queryFactory->newInsert('tblreqloan', $this->toRow($user))
            ->execute()
            ->lastInsertId();
    }

    /**
     * Get user by id.
     *
     * @param int $userId The user id
     *
     * @throws DomainException
     *
     * @return UserData The user
     */
    public function getUserById(int $userId): UserData
    {
        $query = $this->queryFactory->newSelect('tblreqloan');
        $query->select(
            [
                'id',
                'ktp',
                'jml_pinjaman',
                'jangka_waktu',
                'nama_lengkap',
                'jk',
                'tgl_lahir',
                'alamat',
                'telepon',
                'email',
                'kebangsaan',
                'provinsi',
                'gmb_ktp',
                'gmb_diri'
            ]
        );

        $query->andWhere(['id' => $userId]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $userId));
        }

        return new UserData($row);
    }

    /**
     * Update user row.
     *
     * @param UserData $user The user
     *
     * @return void
     */
    public function updateUser(UserData $user): void
    {
        $row = $this->toRow($user);

        $this->queryFactory->newUpdate('tblreqloan', $row)
            ->andWhere(['id' => $user->id])
            ->execute();
    }

    /**
     * Check user id.
     *
     * @param int $userId The user id
     *
     * @return bool True if exists
     */
    public function existsUserId(int $userId): bool
    {
        $query = $this->queryFactory->newSelect('tblreqloan');
        $query->select('id')->andWhere(['id' => $userId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    /**
     * Delete user row.
     *
     * @param int $userId The user id
     *
     * @return void
     */
    public function deleteUserById(int $userId): void
    {
        $this->queryFactory->newDelete('tblreqloan')
            ->andWhere(['id' => $userId])
            ->execute();
    }

    /**
     * Convert to array.
     *
     * @param UserData $user The user data
     *
     * @return array The array
     */
    private function toRow(UserData $user): array
    {
        return [
            'id' => $user->id,
            'ktp' => $user->ktp,
            'jml_pinjaman' => $user->jml_pinjaman,
            'jangka_waktu' => (int)$user->jangka_waktu,
            'nama_lengkap' => $user->nama_lengkap,
            'jk' => $user->jk,
            'tgl_lahir' => $user->tgl_lahir,
            'alamat' => $user->alamat,
            'telepon' => $user->telepon,
            'email' => $user->email,
            'kebangsaan' => $user->kebangsaan,
            'provinsi' => $user->provinsi,
        ];
    }
}
