<?php

namespace App\Http\Controllers;

use App\Events\ClosedQuestions;
use App\Events\GotAskForAnswer;
use App\Events\ChooseQuestion;
use App\Events\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class ClosedQuestionsController
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
        ClosedQuestions::dispatch(
            $request->game,
            $request->questions
        );
        return $this->responseFactory->json(['status' => 'success']);
    }
}
