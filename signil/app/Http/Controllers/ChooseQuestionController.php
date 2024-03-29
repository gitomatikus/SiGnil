<?php

namespace App\Http\Controllers;

use App\Events\GotAskForAnswer;
use App\Events\ChooseQuestion;
use App\Events\QuestionChoosen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class ChooseQuestionController
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
        ChooseQuestion::dispatch(
            $request->get('game'),
            $request->get('round'),
            $request->get('theme'),
            $request->get('question')
        );
        QuestionChoosen::dispatch(
            $request->get('game'),
            $request->get('round'),
            $request->get('theme'),
            $request->get('question')
        );
        return $this->responseFactory->json(['status' => 'success']);
    }
}
