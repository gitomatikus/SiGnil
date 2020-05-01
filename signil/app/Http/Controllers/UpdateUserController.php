<?php

namespace App\Http\Controllers;

use App\Events\GotAskForAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateUserController
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
        $game = $request->get('game');
        $username = $request->get('username');
        $score = $request->get('score');
        /** @var UploadedFile $file */

        if (!$username || $score === null) {
            throw new BadRequestHttpException(__('Username and Score are required'));
        }
        $players = Cache::get('players');
        if (!Arr::get($players, $username)) {
            throw new BadRequestHttpException(__('Player does not exist'));
        }
        $players[$username]['score'] = $score;
        Cache::put('players', $players);

        \App\Events\UpdatePlayers::dispatch($game, $players);
        return $this->responseFactory->json(['status' => 'success']);
    }
}
