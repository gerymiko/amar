<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\SubmissionCreatorRepository;
use App\Exception\ValidationException;
use Respect\Validation\Validator as v;

/**
 * Service.
 */
final class SubmissionCreator
{
    /**
     * @var SubmissionCreatorRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param SubmissionCreatorRepository $repository The repository
     */
    public function __construct(SubmissionCreatorRepository $repository)
    {
        $this->repository = $repository;
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
        $this->validateNewUser($data);

        // Insert user
        $userId = $this->repository->insertUser($data);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $userId;
    }

    /**
     * Input validation.
     *
     * @param array $data The form data
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function validateNewUser(array $data): void
    {
        $errors = [];
        define ("tenor", serialize (array ("3", "6", "12", "18", "36")));
        $tenor = unserialize (tenor);

        define("jk", serialize (array ("L", "P")));
        $jk = unserialize (jk);

        define("kebangsaan", serialize (array ("WNI", "WNA")));
        $kebangsaan = unserialize (kebangsaan);

        if (empty($data['jml_pinjaman'])) {
            $errors['jml_pinjaman'] = 'Input required';
        }
        
        if (empty($data['jangka_waktu'])) {
            $errors['jangka_waktu'] = 'Input required';
        }
        
        if (!in_array($data['jangka_waktu'], $tenor)) {
            $errors['jangka_waktu'] = 'Invalid input';
        }

        if (!in_array($data['jk'], $jk)) {
            $errors['jk'] = 'Invalid input';
        }

        if (!in_array($data['kebangsaan'], $kebangsaan)) {
            $errors['kebangsaan'] = 'Invalid input';
        }

        if (empty($data['jk'])) {
            $errors['jk'] = 'Input required';
        }

        if (empty($data['kebangsaan'])) {
            $errors['kebangsaan'] = 'Input required';
        }

        if (empty($data['ktp'])) {
            $errors['ktp'] = 'Input required';
        }

        if (empty($data['tgl_lahir'])) {
            $errors['tgl_lahir'] = 'Input required';
        }

        $birth = date("Y-m-d", strtotime($data["tgl_lahir"]));
        $diff = date_diff(date_create($birth), date_create(date("Y-m-d")));
        $getbirth = intVal($diff->format('%y'));

        if($getbirth <= 17 || $getbirth >= 80){
            $errors['tgl_lahir'] = 'Age does not meet the requirements';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Input required';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Invalid email address';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}