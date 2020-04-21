<?php

namespace App\Http\Controllers;

use App\Events\ChangeRound;
use App\Events\GotAskForAnswer;
use App\Events\ChooseQuestion;
use App\Events\HideQuestion;
use App\Events\ShowQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class ChangeRoundController
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
        ChangeRound::dispatch(
            $request->get('game'),
            $request->get('round'),
        );
        return $this->responseFactory->json(['status' => 'success']);
    }
}
