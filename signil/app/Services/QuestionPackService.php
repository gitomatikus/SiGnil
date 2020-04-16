<?php

namespace App\Services;

use Illuminate\Support\Arr;

class QuestionPackService
{

    /** @var \ZipArchive */
    private $archive;

    /** @var array */
    private $fileList;

    public function __construct(\ZipArchive $archive, array $fileList)
    {
        $this->archive = $archive;
        $this->fileList = $fileList;
    }

    /**
     * Parse Rounds and return final pack
     * @param array $rounds
     * @return array
     */
    public function parseRounds(array $rounds): array
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

    /**
     * Parse Themes and get questions from them
     * @param array $themes
     * @return array
     */
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

    /**
     * Parse questions depending on it's type
     * @param array $questions
     * @return array
     */
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

    /**
     * Get common questions. Used for mostly parts of theme questions
     *
     * @param array $questions
     * @return array
     */
    private function getCommonQuestions(array $questions): array
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

    /**
     * Questions for final round stored in other way than common questions
     *
     * @param $questions
     * @return array
     */
    private function getFinalRoundQuestions(array $questions): array
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

    /**
     * There may be three types of Atom's (question parts):
     * Single text: Atom = text of question
     * Question: Atom = array that store question and it's type
     * Collection of Questions = array of array with questions
     * @param $atom
     * @return array
     */
    private function getContentFromAtom(array $atom): array
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

    /**
     *  Check if this Atom is collection of Questions
     * @param $atom
     * @return bool
     */
    private function isMultiAtom(array $atom):bool
    {
        return is_array($atom) && isset($atom[0]);
    }

    /**
     * Get media by it's name if it's possible
     *
     * @param $type
     * @param $untypedValue
     * @return string
     */
    private function getValueByType(?string $type, ?string $untypedValue): ?string
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
