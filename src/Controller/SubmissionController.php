<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\User\Service\SubmissionCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SubmissionController
{
    private $submissionCreator;

    public function __construct(SubmissionCreator $submissionCreator)
    {
        $this->submissionCreator = $submissionCreator;
    }

    public function __invoke( ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        // Collect input from the HTTP request
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $userId = $this->submissionCreator->createUser($data);

        // Transform the result into the JSON representation
        $result = [
            'id' => $userId
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}