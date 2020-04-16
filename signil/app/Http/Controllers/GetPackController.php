<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GetPackController
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request, string $hash): JsonResponse
    {
        $pack = Cache::get($hash);
        if (!$pack) {
            throw new BadRequestHttpException(__('Wrong Hash'));
        }
        $pack = \GuzzleHttp\json_decode($pack);
        $response = $this->responseFactory->json($pack);
        unset($pack);
        $response->header('Content-Length', strlen(\GuzzleHttp\json_encode($response->getOriginalContent())));

        return $response;
    }
}
