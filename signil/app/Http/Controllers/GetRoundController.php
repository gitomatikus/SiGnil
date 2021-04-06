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

class GetRoundController
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
        if ($this->storage->exists('game-'. $request->get('game'))) {
            $round = $this->storage->get('game-'. $request->get('game'));
        } else {
            $round = 1;
        }
        return $this->responseFactory->json(['status' => 'success', 'round' => $round]);
    }
}
