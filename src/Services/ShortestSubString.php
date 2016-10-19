<?php

namespace Services;

use Application\ServiceInterface;

/**
 * Class ShortestSubString
 * @package Services
 */
class ShortestSubString implements ServiceInterface
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var int
     */
    private $stringLength;

    /**
     * @var int
     */
    private $patternLength;

    /**
     * @var array
     */
    private $patternCharCount = [];

    /**
     * @var array
     */
    private $foundCharInStringCount = [];

    /**
     * @var int
     */
    private $minimumSubStringLength;

    /**
     * @var int
     */
    private $startPoiner;

    /**
     * @var int
     */
    private $endPointer;

    /**
     * @param $string
     */
    public function setStream($string)
    {
        $this->string = $string;
        $this->stringLength = $this->minimumSubStringLength = strlen($this->string);
    }

    public function getStream()
    {
        return $this->string;
    }

    /**
     * @param $string
     */
    public function setSearchCharacters($string)
    {
        $this->pattern = $string;
        $this->patternLength = strlen($this->pattern);
    }

    public function getSearchCharacters()
    {
        return $this->pattern;
    }

    /**
     *
     */
    public function getMinimumSubString()
    {
        $this->buildPatternCharCountTable();
        $count = 0;
        for ($begin = 0, $end = 0; $end < $this->stringLength; $end++) {

            $this->foundCharInStringCount[$this->string[$end]] = isset($this->foundCharInStringCount[$this->string[$end]]) ? $this->foundCharInStringCount[$this->string[$end]] : 0;
            $this->patternCharCount[$this->string[$end]] = isset($this->patternCharCount[$this->string[$end]]) ? $this->patternCharCount[$this->string[$end]] : 0;

            // skip characters not in $this->pattern
            if ($this->patternCharCount[$this->string[$end]] == 0) {
                continue;
            }

            $this->foundCharInStringCount[$this->string[$end]]++;
            if ($this->foundCharInStringCount[$this->string[$end]] <= $this->patternCharCount[$this->string[$end]]) {
                $count++;
            }

            $this->checkIfConstraintIsSatisfied($count, $begin, $end);
        }
    }

    /**
     *
     */
    private function buildPatternCharCountTable()
    {
        for ($i = 0; $i < $this->patternLength; $i++) {
            if (isset($this->patternCharCount[$this->pattern[$i]])) {
                $this->patternCharCount[$this->pattern[$i]]++;
            } else {
                $this->patternCharCount[$this->pattern[$i]] = 1;
            }

        }
    }

    /**
     * check if window constraint is satisfied
     * advance begin index as far right as possible,
     * stop when advancing breaks window constraint.
     * @param $count
     * @param $begin
     * @param $end
     */
    private function checkIfConstraintIsSatisfied($count, &$begin, $end)
    {
        if ($count == $this->patternLength) {

            while ($this->patternCharCount[$this->string[$begin]] == 0 ||
                $this->foundCharInStringCount[$this->string[$begin]] > $this->patternCharCount[$this->string[$begin]]) {
                if ($this->foundCharInStringCount[$this->string[$begin]] > $this->patternCharCount[$this->string[$begin]]) {
                    $this->foundCharInStringCount[$this->string[$begin]]--;
                }
                $begin++;
            }

            $this->updateMinimumSubStringLength($begin, $end);
        }
    }

    /**
     * update minimumSubStringLength if a minimum length is met
     * @param $begin
     * @param $end
     */
    private function updateMinimumSubStringLength($begin, $end)
    {
        $windowLen = $end - $begin + 1;
        if ($windowLen < $this->minimumSubStringLength) {
            $this->startPoiner = $begin;
            $this->endPointer = $end;
            $this->minimumSubStringLength = $windowLen;
        }
    }

    /**
     * @return int
     */
    public function getStartPointer()
    {
        return $this->startPoiner;
    }

    /**
     * @return int
     */
    public function getEndPointer()
    {
        return $this->endPointer;
    }

    /**
     * @param bool $http
     * @return mixed
     */
    public function getHighlightedStream($http = true)
    {
        $found = substr($this->string, $this->startPoiner, $this->getShortestSubStringLength());
        if ($http) {
            return preg_replace("/($found)/i", "<b><u>$0</u></b>", $this->string);
        }
        return preg_replace("/($found)/i", "\e[3;4;33m$0\e[0m", $this->string);

    }

    /**
     * @return int
     */
    public function getShortestSubStringLength()
    {
        return $this->endPointer && $this->startPoiner ? ($this->endPointer - $this->startPoiner + 1) : 0;
    }


}