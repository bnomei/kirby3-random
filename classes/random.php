<?php

namespace Bnomei;

class Random
{
    protected static function random_int($min, $max)
    {
        return function_exists('random_int') ? random_int($min, $max) : rand($min, $max);
    }

    public static function random($random, $type = false, $length = false, $calle = '')
    {

    // LIST
        if (count($random) > 1) {
            if (\strtolower($type) == 'between') {
                $min = intval($random[0]);
                $max = intval($random[count($random)-1]);
                return (string)self::random_int($min, $max);
            } elseif (\strtolower($type) == 'pool') {
                $l = $length && $length <= count($random) ? $length : count($random);
                if ($l == count($random)) {
                    $s = $random;
                    shuffle($s);
                    return implode(', ', $s);
                } else {
                    $poolkeys = array_rand($random, $l);
                    return implode(', ', array_intersect_key($random, array_flip($poolkeys)));
                }
            } else {
                return (string)$random[self::random_int(0, count($random)-1)];
            }
        }

        // LOREM using https://github.com/joshtronic/php-loremipsum
        elseif ($length && count($random) > 0 && \strtolower($random[0]) == 'lorem') {
            $lipsum = new \joshtronic\LoremIpsum();
            if ($type == 'sentences') {
                return $lipsum->sentences($length);
            } elseif ($type == 'paragraphs') {
                if ($calle == 'site::method') {
                    $pa = $lipsum->paragraphsArray($length);
                    $re = '';
                    foreach ($pa as $p) {
                        $re .= '<p>'.$p.'</p>';
                    }
                    return $re;
                } else {
                    return $lipsum->paragraphs($length);
                }
            } elseif ($type == 'chars') {
                return \substr($lipsum->words($length), 0, $length);
            } else {
                return $lipsum->words($length);
            }
        }

        // RANDOM positive non-zero number
        elseif ($length && \strtolower($type) == 'number') {
            return (string)self::random_int(1, $length);
        }

        // RANDOM token
        elseif ($length && count($random) > 0 && \strtolower($random[0]) == 'token') {
            // $withLower = true, $withUpper = true, $withNumbers = true
            return (string)self::getToken(
                $length,
                \Kirby\Toolkit\Str::contains($type, 'lower'),
                \Kirby\Toolkit\Str::contains($type, 'upper'),
                \Kirby\Toolkit\Str::contains($type, 'number')
            );
        }

        // RANDOM string
        else {
            $l = $length ? $length : intval($random[0]);
            $t = $type ? $type : false;
            return $t ? static::randomString($l, $t) : static::randomString($l);
        }
    }

    // NOTE: copied str::random, str::quickRandom, str::pool from kirby cms v2
    // https://github.com/getkirby/toolkit/blob/master/lib/str.php#L457
    public static function randomString($length = false, $type = 'alphaNum')
    {
        // fall back to insecure str::quickRandom() on PHP < 7
        if (!function_exists('random_int') || !function_exists('random_bytes')) {
            return static::quickRandom($length, $type);
        }
        if (!$length) {
            $length = \random_int(5, 10);
        }
        $pool = static::pool($type, false);
        // catch invalid pools
        if (!$pool) {
            return false;
        }
        // regex that matches all characters *not* in the pool of allowed characters
        $regex = '/[^' . $pool . ']/';
        // collect characters until we have our required length
        $result = '';
        while (($currentLength = strlen($result)) < $length) {
            $missing = $length - $currentLength;
            $bytes = \random_bytes($missing);
            $result .= substr(preg_replace($regex, '', \base64_encode($bytes)), 0, $missing);
        }
        return $result;
    }
    public static function quickRandom($length = false, $type = 'alphaNum')
    {
        if (!$length) {
            $length = rand(5, 10);
        }
        $pool = static::pool($type, false);
        // catch invalid pools
        if (!$pool) {
            return false;
        }
        return \substr(\str_shuffle(\str_repeat($pool, $length)), 0, $length);
    }
    public static function pool($type, $array = true)
    {
        $pool = array();
        if (is_array($type)) {
            foreach ($type as $t) {
                $pool = \array_merge($pool, static::pool($t));
            }
        } else {
            switch ($type) {
        case 'alphaLower':
          $pool = range('a', 'z');
          break;
        case 'alphaUpper':
          $pool = range('A', 'Z');
          break;
        case 'alpha':
          $pool = static::pool(array('alphaLower', 'alphaUpper'));
          break;
        case 'num':
          $pool = \range(0, 9);
          break;
        case 'alphaNum':
          $pool = static::pool(array('alpha', 'num'));
          break;
      }
        }
        return $array ? $pool : \implode('', $pool);
    }

    // http://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string/13733588#13733588
    public static function cryptoRandSecure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min;
        } // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = \hexdec(\bin2hex(\openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public static function getToken($length = 40, $withLower = true, $withUpper = true, $withNumbers = true)
    {
        $token = "";
        $codeAlphabet = "";
        if ($withUpper) {
            $codeAlphabet .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        if ($withLower) {
            $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        }
        if ($withNumbers) {
            $codeAlphabet .= "0123456789";
        }
        $max = strlen($codeAlphabet); // edited
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::cryptoRandSecure(0, $max-1)];
        }
        return $token;
    }
}
