<?php

namespace Bnomei;

use Exception;
use joshtronic\LoremIpsum;
use Kirby\Toolkit\A;
use function base64_encode;
use function implode;
use function random_bytes;
use function random_int;
use function range;
use function strtolower;
use function substr;

final class Random
{
    /**
     * @param null $random
     * @param string|null $generator
     * @param int|null $length
     * @return array|int|mixed|string
     * @throws Exception
     */
    public static function generate($random = null, ?string $generator = null, ?int $length = null)
    {
        if ($generator && is_string($generator)) {
            $generator = strtolower($generator);

            if ($generator === 'number') {
                return self::number(0, intval($random));

            } elseif ($generator === 'string') {
                return self::string(intval($length), strval($random));

            } elseif ($generator === 'pick') {
                return self::pick(intval($length), strval($random));

            } elseif ($generator === 'between') {
                return self::between($random);

            } elseif ($generator === 'lorem') {
                return self::lorem(intval($length), strval($random));

            } elseif ($generator === 'token') {
                return self::token(intval($length), strval($random));
            }
        }

        return self::string($random);
    }

    /**
     * @param int|null $length
     * @param null $random
     * @return array|mixed
     */
    public static function pick(?int $length = 3, $random = null)
    {
        $pool = is_array($random) ? $random : str_split(self::pool(strval($random), ''));
        if (is_null($length)) {
            $length = 1;
        }
        $result = [];
        for ($items = 0; $items < $length; $items++) {
            $result[] = $pool[self::number(0, count($pool) - 1)];
        }
        return count($result) === 1 ? $result[0] : $result;
    }

    /**
     * @param null $random
     * @return int
     */
    public static function between($random = null): int
    {
        if (is_null($random) || (is_string($random) && strlen($random) === 0)) {
            $random = [0, PHP_INT_MAX - 1];
        }
        if (is_string($random)) {
            $random = explode(',', str_replace(' ', '', $random));
        }
        $min = intval(A::get($random, 0, 0));
        $max = intval(A::get($random, 1, PHP_INT_MAX - 1));
        return self::number($min, $max);
    }

    /**
     * @param int|null $length
     * @param string $random
     * @return string
     * @throws Exception
     */
    public static function string(?int $length = null, string $random = 'alphanum'): string
    {
        $random = strtolower($random);
        if (is_null($length)) {
            $length = random_int(5, 10);
        }

        $pool = self::pool($random, '');
        // regex that matches all characters *not* in the pool of allowed characters
        $regex = '/[^' . $pool . ']/';
        // collect characters until we have our required length
        $result = '';
        while (($currentLength = strlen($result)) < $length) {
            $missing = $length - $currentLength;
            $bytes = random_bytes($missing);
            $result .= substr(preg_replace($regex, '', base64_encode($bytes)), 0, $missing);
        }
        return $result;
    }

    /**
     * http://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string/13733588#13733588
     *
     * @param int $length
     * @param bool $withLower
     * @param bool $withUpper
     * @param bool $withNumbers
     * @return string
     */
    public static function token(int $length = 40, string $random = 'alphanum'): string
    {
        $codeAlphabet = self::pool($random, '');
        $max = strlen($codeAlphabet);
        if ($max === 0) {
            return self::token($length);
        }
        $token = [];
        for ($i = 0; $i < $length; $i++) {
            $token[] = $codeAlphabet[random_int(0, $max - 1)];
        }
        return implode($token);
    }

    /**
     * @param string|null $random
     * @param int $length
     * @return string
     */
    public static function lorem(int $length = 3, string $random = null): string
    {
        $lipsum = new LoremIpsum();
        $random = strtolower($random);
        if ($random === 'sentences') {
            return $lipsum->sentences($length);

        } elseif ($random === 'paragraphs') {
            return implode(
                PHP_EOL . PHP_EOL,
                $lipsum->paragraphsArray($length)
            );

        } elseif ($random === 'words') {
            return $lipsum->words($length);

        } elseif ($random === 'chars') {
            return substr($lipsum->words($length), 0, $length);

        }

        return $lipsum->words($length);
    }

    /**
     * @param $random
     * @param string|null $implode
     * @return array|string
     */
    public static function pool($random, ?string $implode = null)
    {
        $pool = [];
        if (is_array($random)) {
            $pool = $random;
        }
        if (is_string($random) && strpos($random, ',') !== false) {
            $randoms = explode(',', str_replace(' ', '', $random));
            foreach ($randoms as $rand) {
                $pool[] = self::pool($rand, $implode);
            }
            $pool = $pool;
        }
        if (count($pool) === 0 && is_string($random)) {
            $random = strtolower($random);
            switch ($random) {
                case 'alphalower':
                    $pool = range('a', 'z');
                    break;
                case 'alphaupper':
                    $pool = range('A', 'Z');
                    break;
                case 'alpha':
                    $pool = self::pool('alphalower, alphaupper', $implode);
                    break;
                case 'num':
                    $pool = range(0, 9);
                    break;
                case 'alphanum':
                    $pool = self::pool('alpha, num', $implode);
                    break;
                default:
                    $pool = $random;
                    break;
            }
        }
        return is_null($implode) || is_string($pool) ? $pool : implode($implode, $pool);
    }

    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function number(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
