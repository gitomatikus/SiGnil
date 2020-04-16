<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Arr;
use Mtownsend\XmlToArray\XmlToArray;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ZanySoft\Zip\Zip;

class GetFileController
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /** @var \ZipArchive */
    private $archive;

    /** @var array */
    private $fileList;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $file = $request->file;
        try {
            $zip = Zip::open($file);
            $list = $zip->listFiles();
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('Not valid Pack'));
        }
        foreach ($list as $index => $element) {
            $this->fileList[rawurldecode($element)] = $index;
        }
        $this->archive = $zip->getArchive();
        $xml = $this->archive->getFromName('content.xml');

        if (!$xml) {
            throw new BadRequestHttpException(__('Content file does not exist'));
        }
        $pack = XmlToArray::convert($xml);
        $rounds = Arr::get($pack, 'rounds.round');
        if (!$rounds) {
            throw new BadRequestHttpException(__('Not valid Pack'));
        }
        $parsedRounds = $this->parseRounds($rounds);

        $pack = [
            'author' => Arr::get($pack, 'info.authors.author'),
            'rounds' => $parsedRounds,
        ];

        $response = $this->responseFactory->json(['status' => 'success', 'pack' => $pack]);
        $pack = null;
        $response->header('Content-Length', strlen(\GuzzleHttp\json_encode($response->getOriginalContent())));
        return $response;
    }


    private function parseRounds(array $rounds): array
    {
        $parsedRounds = [];
        foreach ($rounds as $round) {
            $themes = Arr::get($round, 'themes.theme');
            $parsedThemes = $this->parseThemes($themes);
            $parsedRound = [
                'name' => Arr::get($round, '@attributes.name'),
                'themes' => $parsedThemes
            ];
            $parsedRounds[] = $parsedRound;
        }
        return $parsedRounds;
    }

    private function parseThemes(array $themes): array
    {
        $parsedThemes = [];
        foreach ($themes as $theme) {
            $questions = Arr::get($theme, 'questions.question');
            $parsedQuestions = $this->parseQuestions($questions);
            $parsedTheme = [
                'name' => Arr::get($theme, '@attributes.name'),
                'questions' => $parsedQuestions
            ];
            $parsedThemes[] = $parsedTheme;
        }
        return $parsedThemes;
    }

    private function parseQuestions(array $questions): array
    {
        if ($this->isMultiAtom($questions)) {
            $parsedQuestions = $this->getCommonQuestions($questions);
        } else {
            //Final round
            $parsedQuestions = $this->getFinalRoundQuestions($questions);
        }
        return $parsedQuestions;
    }

    private function getCommonQuestions($questions)
    {
        $parsedQuestions = [];
        foreach ($questions as $question) {
            $specialType = Arr::get($question, 'type.@attributes.name');
            $specialParams = [];
            if ($specialType) {
                $special = Arr::get($question, 'type.param');
                if (is_array($special)) {
                    foreach ($special as $param) {
                        $specialParams[Arr::get($param, '@attributes.name')] = Arr::get($param, '@content');
                    }
                } else {

                    $specialParams[Arr::get($question, 'type.@attributes.name')] = Arr::get($question, 'type.@content');
                }
            }
            $atom = Arr::get($question, 'scenario.atom');
            if (!$atom) {
                $atom = Arr::get($question, 'atom');
            }
            $content = $this->getContentFromAtom($atom);
            $answer = Arr::get($question, 'right.answer');
            $parsedQuestion = [
                'special' => $specialParams,
                'price' => Arr::get($question, '@attributes.price'),
                'scenario' => $content,
                'answer' => $answer
            ];

            $parsedQuestions[] = $parsedQuestion;
        }
        return $parsedQuestions;
    }

    private function getFinalRoundQuestions($questions)
    {
        $parsedQuestions = [];
        $atom = Arr::get($questions, 'scenario.atom');

        $content = $this->getContentFromAtom($atom);
        $answer = Arr::get($questions, 'right.answer');

        $parsedQuestion = [
            'special' => 'final',
            'price' => Arr::get($questions, '@attributes.price'),
            'scenario' => $content,
            'answer' => $answer
        ];

        $parsedQuestions[] = $parsedQuestion;
        return $parsedQuestions;
    }

    private function getContentFromAtom($atom)
    {
        $content = [];
        if ($this->isMultiAtom($atom)) {
            foreach ($atom as $item) {
                $type = Arr::get($item, '@attributes.type');
                $value = $this->getValueByType($type, Arr::get($item, '@content'));
                $content[$type] = $value;
            }
        } else if (is_array($atom)) {
            $type = Arr::get($atom, '@attributes.type', 'say');
            $content[$type] = $this->getValueByType($type, Arr::get($atom, '@content'));
        } else {
            $content['say'] = $atom;
        }
        return $content;
    }

    private function isMultiAtom($atom)
    {
        return is_array($atom) && isset($atom[0]);
    }

    private function convertName($name)
    {
        $name = ltrim($name, '@');
        $name = rawurlencode($name);
        $name = str_replace('%2B', '+', $name); //exception for some reason
        return $name;
    }

    private function getValueByType($type, $untypedValue)
    {
        $value = ltrim($untypedValue, '@');

        if ($type === 'image') {
            $value = base64_encode($this->archive->getFromIndex(Arr::get($this->fileList, 'Images/' . $value)));
        }
        if ($type === 'voice') {
            $value = base64_encode($this->archive->getFromIndex(Arr::get($this->fileList, 'Audio/' . $value)));
        }
        if ($type === 'video') {
            $value = base64_encode($this->archive->getFromIndex(Arr::get($this->fileList, 'Video/' . $value)));
        }
        return $value;
    }
}
