<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Filesystem\FilesystemManager as Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GetPackController
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var Storage|\Illuminate\Contracts\Filesystem\Filesystem
     */
    private $storage;

    public function __construct(ResponseFactory $responseFactory, Storage $storage)
    {
        $this->responseFactory = $responseFactory;
        $this->storage = $storage;
    }

    public function __invoke(Request $request, string $hash): Response
    {
        if ($hash === 'current') {
            if ($this->storage->exists('current')) {
                $hash = $this->storage->get('current');
                return $this->responseFactory->json(['hash' => $hash]);
            }
        }
        $pack = $this->storage->exists($hash);
        if (!$pack) {
            throw new BadRequestHttpException(__('Wrong Hash'));
        }

        return $this->storage->download($hash, 'pack.json');
    }
}
