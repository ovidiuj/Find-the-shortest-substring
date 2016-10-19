<?php

namespace Services;


use Application\ServiceInterface;

/**
 * Class RandomStringGenerator
 * @package Services
 */
class RandomStringGenerator implements ServiceInterface
{
    /**
     * @var int
     */
    protected $length = 1000;

    /**
     * @var string
     */
    protected $alphabet;

    /**
     * @var int
     */
    protected $alphabetLength;

    /**
     * RandomStringGenerator constructor.
     */
    public function __construct()
    {
        $this->alphabet = implode(range('a', 'z')) . implode(range('A', 'Z'));
        $this->alphabetLength = strlen($this->alphabet);
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    public function getAlphabet()
    {
        return $this->alphabet;
    }

    /**
     * @param string $alphabet
     */
    public function setAlphabet($alphabet = null)
    {
        $this->alphabet = $alphabet;
        $this->alphabetLength = strlen($alphabet);
    }

    /**
     * @return string
     */
    public function generate()
    {
        $token = '';

        for ($i = 0; $i < $this->length; $i++) {
            $randomKey = $this->getRandomInteger(0, $this->alphabetLength);
            $token .= $this->alphabet[$randomKey];
        }

        return $token;
    }

    /**
     * @param $min
     * @param $max
     * @return mixed
     */
    protected function getRandomInteger($min, $max)
    {
        $range = ($max - $min);

        if ($range < 0) {
            // Not so random...
            return $min;
        }

        $log = log($range, 2);

        // Length in bytes.
        $bytes = (int)($log / 8) + 1;

        // Length in bits.
        $bits = (int)$log + 1;

        // Set all lower bits to 1.
        $filter = (int)(1 << $bits) - 1;

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));

            // Discard irrelevant bits.
            $rnd = $rnd & $filter;

        } while ($rnd >= $range);

        return ($min + $rnd);
    }
}