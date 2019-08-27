# Kirby 3 Random

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-random?color=ae81ff)
![Stars](https://flat.badgen.net/packagist/ghs/bnomei/kirby3-random?color=272822)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-random?color=272822)
![Issues](https://flat.badgen.net/packagist/ghi/bnomei/kirby3-random?color=e6db74)
[![Build Status](https://flat.badgen.net/travis/bnomei/kirby3-random)](https://travis-ci.com/bnomei/kirby3-random)
[![Coverage Status](https://flat.badgen.net/coveralls/c/github/bnomei/kirby3-random)](https://coveralls.io/github/bnomei/kirby3-random) 
[![Demo](https://flat.badgen.net/badge/website/examples?color=f92672)](https://kirby3-plugins.bnomei.com/autoid) 
[![Gitter](https://flat.badgen.net/badge/gitter/chat?color=982ab3)](https://gitter.im/bnomei-kirby-3-plugins/community) 
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)


Kirby Tag and Page Method to generate various random values.

## Commercial Usage

This plugin is free but if you use it in a commercial project please consider to 
- [make a donation 🍻](https://www.paypal.me/bnomei/4) or
- [buy me ☕](https://buymeacoff.ee/bnomei) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/35731?link=1170)

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-random/archive/master.zip) as folder `site/plugins/kirby3-random` or
- `git submodule add https://github.com/bnomei/kirby3-random.git site/plugins/kirby3-random` or
- `composer require bnomei/kirby3-random`

## Usage

| Regex/Info | Kirbytag | Page-Method | Static |
|-------|----------|-------------|--------|
| `[0-9]{1,3}` | `(random: 999 generator: number)` | `$page->random(999)` | `Random::number(0, 999)` |
| `[0-9]{1}` | `(random: 0, 9 generator: between)` | `$page->random([0, 9], 'between')` | `Random::between([0, 9])` |
| `(\d\w){5,10}` | `(random:)` | `$page->random()` | `Random::string()` |
| `\d{5}` | `(random: 5 generator: num)` | ` $page->random(5, 'num')` | `Random::string(5, 'num')` |
| `(apple OR banana OR coconut)` | `(random: apple, banana, coconut generator: pick)` | `$page->random('apple, banana, coconut', 'pick')` | `Random::pick(['apple', 'banana', 'coconut'])` |
| `5 chars` | `(random: chars generator: lorem length: 5)` | `$page->random('chars', 'lorem', 5)` | `Random::lorem(5, 'chars')` |
| `5 words` | `(random: words generator: lorem length: 5)` | `$page->random('words', 'lorem', 5)` | `Random::lorem(5, 'words')` |
| `5 sentences` | `(random: sentences generator: lorem length: 5)` | `$page->random('sentences', 'lorem', 5)` | `Random::lorem(5, 'sentences')` |
| `5 [paragaph PHP_EOL PHP_EOL]` | `(random: paragraphs generator: lorem length: 5)` | `$page->random('paragraphs', 'lorem', 5)` | `Random::lorem(5, 'paragraphs')` |
| `[a-zA-Z0-9]{40}` | `(random: generator: token)` | ` $page->random(null, 'token')` | `Random::token()` |
| `[A-Z0-9]{12}` | `(random: alphaupper, num generator: token length: 12)` | ` $page->random('alphaupper, num', 'token', 12)` | `Random::token(12, 'alphaupper, num')` |

> Markdown tables do not allow `|` in regex so i wrote ` OR ` instead.
> For all random numbers the cryptographically safe PHP 7 function `rand_int` is used. `Bnomei\Random` can be used as `Random` if `use Bnomei\Random;` is included at the head of the PHP script.

## Dependencies

- [joshtronic/php-loremipsum](https://github.com/joshtronic/php-loremipsum)

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-random/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
