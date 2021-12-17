<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Respect\Validation\Exceptions\NestedValidationException;

final class RespectValidationMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * The constructor.
     *
     * @param ResponseFactoryInterface $responseFactory The response factory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function process(
        ServerRequestInterface $request, 
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch(NestedValidationException $exception) {
            $messages = [];
            /** @var ValidationException $message */
            foreach($exception->getIterator() as $message) {
                $key = $message->getParam('name');
                if($key === null) {
                    continue;
                }
                $messages[$key] = $message->getMessage();
            }
            
            $response = $this->responseFactory->createResponse();
        
            $result = [
                'error' => [
                    'message' => $exception->getMessage(),
                    'details' => $messages,
                ],
            ];
            $response->getBody()->write(json_enode($result));
            $response->withHeader('Content-Type', 'application/json');

            return $response->withStatus(422);
        }
    }
}