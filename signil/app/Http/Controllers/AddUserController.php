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

class AddUserController
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
        if(strlen($username)> 50) {
            throw new BadRequestHttpException(__('Username is too long'));
        }
        $username = mb_convert_encoding($username, "UTF-8", "UTF-8");
        $host = $request->get('host');
        /** @var UploadedFile $file */
        $file = $request->img;
        if ($file && $file->getSize() > 1024 * 1024) {
            throw new BadRequestHttpException(__('Too Large Image'));
        }
        if (!$username && !$host) {
            throw new BadRequestHttpException(__('Username is required'));
        }

        $players = Cache::get('players') ?: [];

        if ($host) {
            foreach ($players as $player) {
                $player['host'] = false;
            }
        }
        if ($username) {
            if (!Arr::get($players, $username)) {
                $players[$username]['name'] = $username;
                $players[$username]['score'] = 0;
                $players[$username]['host'] = $host;
            }
            if ($file) {
                $fileContent = base64_encode(file_get_contents($file->path()));
                $players[$username]['img'] = $fileContent;
            }
            Cache::put('players', $players);
        }
        \App\Events\UpdatePlayers::dispatch($game, $players);
        \App\Events\UpdateHost::dispatch($game, $players);

        return $this->responseFactory->json(['status' => 'success']);
    }
}
