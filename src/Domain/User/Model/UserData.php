<?php

namespace App\Domain\User\Model;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class UserData
{
    public ?int $id = null;

    public ?string $ktp = null;

    public ?string $jml_pinjaman = null;

    public ?int $jangka_waktu = null;

    public ?string $nama_lengkap = null;

    public ?string $jk = null;

    public ?string $tgl_lahir = null;

    public ?string $alamat = null;

    public ?string $telepon = null;

    public ?string $email = null;

    public ?string $kebangsaan = null;

    public ?string $provinsi = null;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->id = $reader->findInt('id');
        $this->ktp = $reader->findString('ktp');
        $this->jml_pinjaman = $reader->findString('jml_pinjaman');
        $this->jangka_waktu = $reader->findInt('jangka_waktu');
        $this->nama_lengkap = $reader->findString('nama_lengkap');
        $this->jk = $reader->findInt('jk');
        $this->tgl_lahir = $reader->findString('tgl_lahir');
        $this->alamat = $reader->findString('alamat');
        $this->telepon = $reader->findString('telepon');
        $this->email = $reader->findString('email');
        $this->kebangsaan = $reader->findString('kebangsaan');
        $this->provinsi = $reader->findString('provinsi');
    }
}
