<?php
declare(strict_types=1);

namespace App\Domain\Model;

use JsonSerializable;

class UserModel implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $ktp;

    /**
     * @var string
     */
    private $email;

    /**
     * @param int|null  $id
     * @param string    $ktp
     * @param string    $email
     */
    public function __construct(?int $id, string $ktp, string $email)
    {
        $this->id = $id;
        $this->ktp = strtolower($ktp);
        $this->email = ucfirst($email);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKtp(): string
    {
        return $this->ktp;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'ktp' => $this->ktp,
            'email' => $this->email
        ];
    }
}
