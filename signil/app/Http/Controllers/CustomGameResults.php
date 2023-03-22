<?php

namespace App\Http\Controllers;

use App\Events\GotAskForAnswer;
use App\Events\ChooseQuestion;
use App\Events\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Cache;

class CustomGameResults
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
        $customGame = $request->customName;

        if ($request->clear) {
            Cache::forget($customGame . 'game-' . $request->game);
            return $this->responseFactory->json(['status' => 'success']);
        }
        $results = Cache::get($customGame . 'game-' . $request->game);
        if ($results) {
            $results = json_decode($results, true);
            if ($request->results) {
                $results[] = $request->results;
            }
        } else {
            if ($request->results) {
                $results = [$request->results];
            }
        }
        Cache::put($customGame . 'game-' . $request->game, json_encode($results));
        \App\Events\CustomGameResults::dispatch(
            $request->game,
            $results
        );
        return $this->responseFactory->json(['status' => 'success']);
    }
}
