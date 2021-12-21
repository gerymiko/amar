<?php

namespace App\Domain\User\Service;

use App\Domain\User\Model\UserData;
use App\Domain\User\Repository\SubmissionRepository;
use App\Domain\User\Type\UserTenor;
use App\Domain\User\Type\UserGender;
use App\Domain\User\Type\UserCity;
use App\Exception\ValidationException;
// use Respect\Validation\Validator as v;
// use App\Domain\User\Repository\SubmissionCreatorRepository;
// use App\Domain\User\Model\UserModel;

/**
 * Service.
 */
final class SubmissionCreator
{

    private SubmissionRepository $repository;

    private SubmissionValidator $submissionValidator;

    /**
     * The constructor.
     *
     * @param SubmissionRepository $repository The repository
     */
    public function __construct(
        SubmissionRepository $repository,
        SubmissionValidator $submissionValidator
    ) {
        $this->repository = $repository;
        $this->submissionValidator = $submissionValidator;
    }

    /**
     * Create a new user.
     *
     * @param array $data The form data
     *
     * @return int The new user ID
     */
    public function createUser(array $data): int
    {
        // Input validation
        $this->submissionValidator->validateUser($data);

        // Map form data to user DTO (model)
        $user = new UserData($data);

        // Insert user
        $userId = $this->repository->insertUser($user);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $userId;
    }

}