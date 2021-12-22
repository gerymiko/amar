<?php

namespace App\Domain\User\Service;

use App\Domain\User\Model\UserData;
use App\Domain\User\Repository\SubmissionRepository;

/**
 * Service.
 */
final class SubmissionReader
{
    private SubmissionRepository $repository;

    /**
     * The constructor.
     *
     * @param SubmissionRepository $repository The repository
     */
    public function __construct(SubmissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a user.
     *
     * @param int $userId The user id
     *
     * @return UserData The user data/model
     */
    public function getUserData(int $userId): UserData
    {
        // Fetch data from the database
        $user = $this->repository->getUserById($userId);

        return $user;
    }
}
