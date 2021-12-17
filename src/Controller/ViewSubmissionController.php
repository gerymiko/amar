<?php
declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;

class ViewSubmissionController extends SubmissionController
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);

        // $this->logger->info("User of id `${userId}` was viewed.");

        return $this->respondWithData($user);
    }
}
