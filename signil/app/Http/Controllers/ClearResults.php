<?php

namespace App\Http\Controllers;

use App\Events\GotAskForAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class ClearResults
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request): JsonResponse
    {
        \App\Events\ClearResults::dispatch($request->get('game'));
        return $this->responseFactory->json(['status' => 'success']);
    }

}
