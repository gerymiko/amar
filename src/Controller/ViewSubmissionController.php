<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\User\Model\UserData;
use App\Domain\User\Service\SubmissionReader;
use App\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class ViewSubmissionController
{
    private SubmissionReader $SubmissionReader;

    private Responder $responder;

    /**
     * The constructor.
     *
     * @param SubmissionReader $SubmissionViewer The service
     * @param Responder $responder The responder
     */
    public function __construct(SubmissionReader $SubmissionViewer, Responder $responder)
    {
        $this->SubmissionReader = $SubmissionViewer;
        $this->responder = $responder;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param array $args The routing arguments
     *
     * @return ResponseInterface The response
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Fetch parameters from the request
        $userId = (int)$args['id'];

        // Invoke the domain (service class)
        $user = $this->SubmissionReader->getUserData($userId);

        // Transform result
        return $this->transform($response, $user);
    }

    /**
     * Transform result to response.
     *
     * @param ResponseInterface $response The response
     * @param UserData $user The user
     *
     * @return ResponseInterface The response
     */
    private function transform(ResponseInterface $response, UserData $user): ResponseInterface
    {
        $data = [
            'id' => $user->id,
            'ktp' => $user->ktp,
            'jml_pinjaman' => $user->jml_pinjaman,
            'jangka_waktu' => $user->jangka_waktu,
            'nama_lengkap' => $user->nama_lengkap,
            'jk' => $user->jk,
            'tgl_lahir' => $user->tgl_lahir,
            'alamat' => $user->alamat,
            'telepon' => $user->telepon,
            'email' => $user->email,
            'kebangsaan' => $user->kebangsaan,
            'provinsi' => $user->provinsi,
        ];

        return $this->responder->withJson($response, $data);
    }
}
