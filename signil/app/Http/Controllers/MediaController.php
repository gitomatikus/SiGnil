<?php

namespace App\Http\Controllers;

use App\Events\GotAskForAnswer;
use App\Events\ChooseQuestion;
use App\Events\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class MediaController
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
        Media::dispatch(
            $request->game,
            $request->state
        );
        return $this->responseFactory->json(['status' => 'success']);
    }
}
