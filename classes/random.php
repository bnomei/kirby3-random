<?php

namespace Bnomei;

class Random {

  protected static function random_int($min, $max) {
    return function_exists('random_int') ? random_int($min, $max) : rand($min, $max);
  }

  public static function random($random, $type = false, $length = false, $calle = '') {

    // LIST
    if(count($random) > 1) {
      if(\strtolower($type) == 'between') {
        $min = intval($random[0]);
        $max = intval($random[count($random)-1]);
        return (string)self::random_int($min, $max);

      } else if(\strtolower($type) == 'pool') {
        $l = $length && $length <= count($random) ? $length : count($random);
        if($l == count($random)) {
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
    else if ($length && count($random) > 0 && \strtolower($random[0]) == 'lorem') {
      $lipsum = new \joshtronic\LoremIpsum();
      if($type == 'sentences') {
        return $lipsum->sentences($length);
      }
      else if($type == 'paragraphs') {
        if($calle == 'site::method') {
          $pa = $lipsum->paragraphsArray($length);
          $re = '';
          foreach ($pa as $p) {
            $re .= '<p>'.$p.'</p>';
          }
          return $re;
        } else {
          return $lipsum->paragraphs($length);
        }
      }
      else if($type == 'chars') {
        return \substr($lipsum->words($length), 0, $length);
      }
      else {
        return $lipsum->words($length);
      }
    }

    // RANDOM positive non-zero number
    else if($length && \strtolower($type) == 'number') {
      echo 'BAMM';
      return (string)self::random_int(1, $length);
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
  public static function randomString($length = false, $type = 'alphaNum') {
    // fall back to insecure str::quickRandom() on PHP < 7
    if(!function_exists('random_int') || !function_exists('random_bytes')) {
      return static::quickRandom($length, $type);
    }
    if(!$length) $length = \random_int(5, 10);
    $pool = static::pool($type, false);
    // catch invalid pools
    if(!$pool) return false;
    // regex that matches all characters *not* in the pool of allowed characters
    $regex = '/[^' . $pool . ']/';
    // collect characters until we have our required length
    $result = '';
    while(($currentLength = strlen($result)) < $length) {
      $missing = $length - $currentLength;
      $bytes = \random_bytes($missing);
      $result .= substr(preg_replace($regex, '', \base64_encode($bytes)), 0, $missing);
    }
    return $result;
  }
  public static function quickRandom($length = false, $type = 'alphaNum') {
    if(!$length) $length = rand(5, 10);
    $pool = static::pool($type, false);
    // catch invalid pools
    if(!$pool) return false;
    return \substr(\str_shuffle(\str_repeat($pool, $length)), 0, $length);
  }
  public static function pool($type, $array = true) {
    $pool = array();
    if(is_array($type)) {
      foreach($type as $t) {
        $pool = \array_merge($pool, static::pool($t));
      }
    } else {
      switch($type) {
        case 'alphaLower':
          $pool = range('a','z');
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
}
