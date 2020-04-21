<?php

namespace App\Http\Controllers;

use App\Events\GotAskForAnswer;
use App\Events\ChooseQuestion;
use App\Events\ShowAnswer;
use App\Events\ShowQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class ShowAnswerController
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
        ShowAnswer::dispatch(
            $request->get('game'),
            $request->get('round'),
            $request->get('theme'),
            $request->get('question')
        );
        return $this->responseFactory->json(['status' => 'success']);
    }
}
