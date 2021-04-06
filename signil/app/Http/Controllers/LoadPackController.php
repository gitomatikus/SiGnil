<?php

namespace App\Http\Controllers;

use App\Events\PackHosted;
use App\Services\QuestionPackService;
use Illuminate\Cache\FileStore;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Filesystem\FilesystemManager as Storage;
use Mtownsend\XmlToArray\XmlToArray;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ZanySoft\Zip\Zip;

class LoadPackController
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var Storage| Filesystem
     */
    private $storage;

    public function __construct(ResponseFactory $responseFactory, Storage $storage)
    {
        $this->responseFactory = $responseFactory;
        $this->storage = $storage;
    }

    public function __invoke(Request $request): Response
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
        $pack = $this->storage->exists($hash);
        if ($pack) {
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
            $pack = \GuzzleHttp\json_encode($pack);
            $this->storage->put($hash, $pack);
        }
        $this->storage->put('current', $hash);
        PackHosted::dispatch($request->game, $hash);
        return $this->storage->download($hash, 'pack.json');
    }
}
