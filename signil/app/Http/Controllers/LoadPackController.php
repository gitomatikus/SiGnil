<?php

namespace App\Http\Controllers;

use App\Events\PackHosted;
use App\Services\QuestionPackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Mtownsend\XmlToArray\XmlToArray;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ZanySoft\Zip\Zip;

class LoadPackController
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
        $file = $request->file;
        unset($request->file);
        try {
            $zip = Zip::open($file);
            $list = $zip->listFiles();
            unset($file);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('Not valid Pack'));
        }
        $indexedList = [];
        foreach ($list as $index => $element) {
            $indexedList[rawurldecode($element)] = $index;
        }
        $archive = $zip->getArchive();
        $xml = $archive->getFromName('content.xml');
        if (!$xml) {
            throw new BadRequestHttpException(__('Content file does not exist'));
        }
        $hash = md5($xml);
        $pack = Cache::get($hash);
        if (!$pack) {
            /** @var QuestionPackService $packService */
            $packService = app(QuestionPackService::class, ['archive' => $archive, 'fileList' => $indexedList]);
            $converted = XmlToArray::convert($xml);
            $rounds = Arr::get($converted, 'rounds.round');
            if (!$rounds) {
                throw new BadRequestHttpException(__('Not valid Pack'));
            }
            $author = Arr::get($converted, 'info.authors.author');
            unset($converted);
            $parsedRounds = $packService->parseRounds($rounds);
            unset($rounds);
            $pack = [
                'author' => $author,
                'rounds' => $parsedRounds,
                'hash' => $hash
            ];
            unset($parsedRounds);
        } else {
            $pack = \GuzzleHttp\json_decode($pack, true);
        }
        unset($archive);
        $response = $this->responseFactory->json($pack);
        $pack = \GuzzleHttp\json_encode($pack);
        Cache::put($hash, $pack, now()->addHours(5));
        unset($pack);
        $response->header('Content-Length', strlen(\GuzzleHttp\json_encode($response->getOriginalContent())));
        PackHosted::dispatch($request->game, $hash);
        return $response;
    }
}
