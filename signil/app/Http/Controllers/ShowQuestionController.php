<?php

namespace App\Http\Controllers;

use App\Events\GotAskForAnswer;
use App\Events\ShowQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class ShowQuestionController
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
        ShowQuestion::dispatch($request->get('game'), $request->get('question'));
        return $this->responseFactory->json(['status' => 'success']);
    }

}
