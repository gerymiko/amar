<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\SubmissionRepository;
use App\Domain\User\Type\UserTenor;
use App\Domain\User\Type\UserGender;
use App\Domain\User\Type\UserCity;
use App\Domain\User\Type\UserNationality;
use App\Factory\ValidationFactory;
use Cake\Validation\Validator;
use Selective\Validation\Exception\ValidationException;

/**
 * Service.
 */
final class SubmissionValidator
{
    private SubmissionRepository $repository;

    private ValidationFactory $validationFactory;

    /**
     * The constructor.
     *
     * @param SubmissionRepository $repository The repository
     * @param ValidationFactory $validationFactory The validation
     */
    public function __construct(SubmissionRepository $repository, ValidationFactory $validationFactory)
    {
        $this->repository = $repository;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Validate update.
     *
     * @param int $userId The user id
     * @param array $data The data
     *
     * @return void
     */
    public function validateUserUpdate(int $userId, array $data): void
    {
        if (!$this->repository->existsUserId($userId)) {
            throw new ValidationException(sprintf('User not found: %s', $userId));
        }

        $this->validateUser($data);
    }

    /**
     * Validate new user.
     *
     * @param array $data The data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validateUser(array $data): void
    {
        $validator = $this->createValidator();

        $birth = date("Y-m-d", strtotime($data["tgl_lahir"]));
        $diff = date_diff(date_create($birth), date_create(date("Y-m-d")));
        $getbirth = intVal($diff->format('%y'));

        $validationResult = $this->validationFactory->createValidationResult(
            $validator->validate($data)
        );

        if ($getbirth <= 17 || $getbirth >= 80) {
            $validationResult->addError('tgl_lahir', 'Age does not meet the requirements');
        }

        if ($validationResult->fails()) {
            throw new ValidationException('Please check your input', $validationResult);
        }
    }

    /**
     * Create validator.
     *
     * @return Validator The validator
     */
    private function createValidator(): Validator
    {
        $validator = $this->validationFactory->createValidator();

        return $validator
            ->notEmptyString('ktp', 'Input required')
            ->minLength('ktp', 16, 'Too short')
            ->maxLength('ktp', 16, 'Too long')
            ->notEmptyString('jml_pinjaman', 'Input required')
            ->notEmptyString('jangka_waktu', 'Input required')
            ->notEmptyString('nama_lengkap', 'Input required')
            ->notEmptyString('jk', 'Input required')
            ->notEmptyString('tgl_lahir', 'Input required')
            ->notEmptyString('alamat', 'Input required')
            ->notEmptyString('telepon', 'Input required')
            ->email('email', false, 'Input required')
            ->notEmptyString('kebangsaan', 'Input required')
            ->notEmptyString('provinsi', 'Input required')
            ->inList('jangka_waktu', UserTenor::TENOR, 'Invalid')
            ->inList('jk', UserGender::GENDER, 'Invalid')
            ->inList('provinsi', UserCity::CITY, 'Invalid')
            ->inList('kebangsaan', UserNationality::NATION, 'Invalid');
            
    }
}
