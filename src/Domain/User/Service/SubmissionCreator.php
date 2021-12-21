<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\SubmissionCreatorRepository;
use App\Exception\ValidationException;
use Respect\Validation\Validator as v;
// use App\Domain\User\Model\UserModel;
use App\Domain\User\Model\UserData;
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

        $validator = new v();

        $user = new UserData($data);

        $validator->addRule(v::key('ktp', v::allOf(
            v::notEmpty()->setTemplate('The ktp must not be empty')
        ))->setTemplate('The key "ktp" is required'));
        
        $validator->assert($user);
        var_dump($validator->assert($user));

        exit;
        $validator->addRule(v::key('ktp', v::allOf(
            v::notEmpty()->setTemplate('The ktp must not be empty'),
            v::length(3, 16)->setTemplate('Invalid length'),
            v::digit()->setTemplate('Invalid input')
        ))->setTemplate('The key "ktp" is required'));

        $validator->addRule(v::key('jml_pinjaman', v::allOf(
            v::notEmpty()->setTemplate('The loan must not be empty'),
            v::length(6, 15)->setTemplate('Invalid length'),
            v::digit()->setTemplate('Invalid input')
        ))->setTemplate('The key "loan" is required'));

        $validator->addRule(v::key('jangka_waktu', v::allOf(
            v::notEmpty()->setTemplate('The tenor must not be empty'),
            v::length(6, 15)->setTemplate('Invalid length'),
            v::digit()->setTemplate('Invalid input')
        ))->setTemplate('The key "tenor" is required'));
        
        
        $validator->assert($data);


        define ("tenor", serialize (array ("3", "6", "12", "18", "36")));
        $tenor = unserialize (tenor);

        define("jk", serialize (array ("L", "P")));
        $jk = unserialize (jk);

        define("kebangsaan", serialize (array ("WNI", "WNA")));
        $kebangsaan = unserialize (kebangsaan);

        define("provinsi", serialize (array ("DKI JAKARTA", "JAWA BARAT", "JAWA TIMUR", "SUMATERA UTARA")));
        $provinsi = unserialize (provinsi);

        if (empty($data['ktp'])) {
            $errors['ktp'] = 'Input required';
        }

        if (empty($data['jml_pinjaman'])) {
            $errors['jml_pinjaman'] = 'Input required';
        }
        
        if (empty($data['jangka_waktu'])) {
            $errors['jangka_waktu'] = 'Input required';
        } elseif (!in_array($data['jangka_waktu'], $tenor)) {
            $errors['jangka_waktu'] = 'Invalid input';
        }
        if (empty($data['jk'])) {
            $errors['jk'] = 'Input required';
        } elseif (!in_array($data['jk'], $jk)) {
            $errors['jk'] = 'Invalid input';
        }

        if (empty($data['kebangsaan'])) {
            $errors['kebangsaan'] = 'Input required';
        } elseif (!in_array($data['kebangsaan'], $kebangsaan)) {
            $errors['kebangsaan'] = 'Invalid input';
        }

        $birth = date("Y-m-d", strtotime($data["tgl_lahir"]));
        $diff = date_diff(date_create($birth), date_create(date("Y-m-d")));
        $getbirth = intVal($diff->format('%y'));
        if (empty($data['tgl_lahir'])) {
            $errors['tgl_lahir'] = 'Input required';
        } elseif ($getbirth <= 17 || $getbirth >= 80) {
            $errors['tgl_lahir'] = 'Age does not meet the requirements';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Input required';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Invalid email address';
        }

        if (empty($data['provinsi'])) {
            $errors['provinsi'] = 'Input required';
        } elseif (!in_array($data['provinsi'], $provinsi)) {
            $errors['provinsi'] = 'Province does not meet the requirements';
        }

        // var_dump($errors);
        // exit;
        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}