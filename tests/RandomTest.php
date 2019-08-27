<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Bnomei\Random;
use PHPUnit\Framework\TestCase;

class RandomTest extends TestCase
{
    public function testRandomString()
    {
        $rand = Random::string();
        $this->assertRegExp('/^[\w*\d*]{5,10}$/', $rand);

        $rand = Random::string(23);
        $this->assertRegExp('/^[\w*\d*]{1,23}$/', $rand);

        $rand = Random::string(23, 'num');
        $this->assertRegExp('/^\d{1,23}$/', $rand);
    }

    public function testBetween()
    {
        $rand = Random::between();
        $this->assertIsInt($rand);

        $rand = Random::between('5, 23');
        $this->assertTrue($rand >= 5 && $rand <= 23);

        $rand = Random::between([5, 23]);
        $this->assertTrue($rand >= 5 && $rand <= 23);

        $rand = Random::between([5, 5]);
        $this->assertTrue($rand === 5);
    }

    public function testGetToken()
    {
        $rand = Random::token(5);
        $this->assertTrue(5 === strlen($rand));
        $this->assertRegExp('/^[\w\d]{5}$/', $rand);

        $rand = Random::token(5, 'alphaupper, num');
        $this->assertRegExp('/^[A-Z0-9]{5}$/', $rand);

        $rand = Random::token(5, 'num');
        $this->assertRegExp('/^\d{5}$/', $rand);

        $rand = Random::token(5, ''); // alphanum
        $this->assertRegExp('/^[a-zA-Z0-9]{5}$/', $rand);
    }

    public function testPick()
    {
        $rand = Random::pick(null, ['a', 'b', 'c']);
        $this->assertRegExp('/[abc]{1}/', $rand);

        $rand = Random::pick(1, ['a', 'b', 'c']);
        $this->assertRegExp('/[abc]{1}/', $rand);

        $rand = Random::pick(3, ['a1', 'b2', 'c3']);
        $this->assertRegExp('/(a1|b2|c3){3}/', implode($rand));

        $rand = Random::pick(2, 'alphaupper, num');
        $this->assertRegExp('/[A-Z0-9]{2}/', implode($rand));

        $rand = Random::pick(3, 'num');
        $this->assertRegExp('/[0-9]{3}/', implode($rand));
    }

    public function testPool()
    {
        $rand = Random::pool(['red', 'green', 'blue', 'black', 'white', 'yellow'], '');
        $this->assertRegExp('/redgreenblueblackwhiteyellow/', $rand);

        $rand = Random::pool('red, green, blue, black, white, yellow', '');
        $this->assertRegExp('/redgreenblueblackwhiteyellow/', $rand);

        $rand = Random::pool('alphaLower', '');
        $this->assertEquals('abcdefghijklmnopqrstuvwxyz', $rand);

        $rand = Random::pool('alphaUpper', '');
        $this->assertEquals('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $rand);

        $rand = Random::pool('alpha', '');
        $this->assertEquals('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $rand);

        $rand = Random::pool('num', '');
        $this->assertEquals('0123456789', $rand);

        $rand = Random::pool('alphaNum', '');
        $this->assertEquals('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', $rand);
    }

    public function testLorem()
    {
        $rand = Random::lorem();
        $this->assertCount(3, explode(' ', $rand));

        $rand = Random::lorem(3, 'words');
        $this->assertCount(3, explode(' ', $rand));

        $rand = Random::lorem(5, 'words');
        $this->assertCount(5, explode(' ', $rand));

        $rand = Random::lorem(3, 'chars');
        $this->assertTrue(3 === strlen($rand));

        $rand = Random::lorem(100, 'chars');
        $this->assertTrue(100 === strlen($rand));

        $rand = Random::lorem(5, 'paragraphs');
        $this->assertCount(5, explode(PHP_EOL . PHP_EOL, $rand));

        $rand = Random::lorem(5, 'sentences');
        $this->assertCount(5, explode('. ', $rand));
    }

    public function testGenerate()
    {
        $this->assertNotNull(Random::generate( 10, 'number'));
        $this->assertNotNull(Random::generate( 5, 'string'));
        $this->assertNotNull(Random::generate( 'a, b, c', 'pick'));
        $this->assertNotNull(Random::generate( '5, 10', 'between'));
        $this->assertNotNull(Random::generate( 'sentences', 'lorem'));
        $this->assertNotNull(Random::generate( 'alphanum', 'token'));
        $this->assertNotNull(Random::generate());
    }
}
