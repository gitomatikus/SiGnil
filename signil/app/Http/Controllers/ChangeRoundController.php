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
use Illuminate\Filesystem\FilesystemManager as Storage;

class ChangeRoundController
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var ResponseFactory
     */
    private $storage;

    public function __construct(ResponseFactory $responseFactory, Storage $storage)
    {
        $this->responseFactory = $responseFactory;
        $this->storage = $storage;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->storage->put('game-'. $request->get('game'), $request->get('round'));
        ChangeRound::dispatch(
            $request->get('game'),
            $request->get('round'),
        );
        return $this->responseFactory->json(['status' => 'success']);
    }
}
