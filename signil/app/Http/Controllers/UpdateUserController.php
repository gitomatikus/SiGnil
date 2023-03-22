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
        $username = substr($username,0,50);
        $control = $request->get('control');
        $score = $request->get('score');
        $title = $request->get('title') ?? '';
        /** @var UploadedFile $file */

        if (!$username) {
            throw new BadRequestHttpException(__('Username and Score are required'));
        }
        $players = Cache::get('players');
        if (!Arr::get($players, $username)) {
            throw new BadRequestHttpException(__('Player does not exist'));
        }
        $players[$username]['score'] = $score ?? $players[$username]['score'];
        if ($control) {
            foreach ($players as &$player) {
                $player['control'] = false;
            }
            $players[$username]['control'] = true;
        }
        if (isset($title)) {
            $players[$username]['title'] = $title;
        }
        Cache::put('players', $players);

        \App\Events\UpdatePlayers::dispatch($game, $players);
        return $this->responseFactory->json(['status' => 'success']);
    }
}
