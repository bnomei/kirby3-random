# Kirby 3 Random

![GitHub release](https://img.shields.io/github/release/bnomei/kirby3-random.svg?maxAge=1800) ![License](https://img.shields.io/github/license/mashape/apistatus.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-3%2B-red.svg)

Kirby Tag and Page Method to generate various random values.

This plugin is free but if you use it in a commercial project please consider to [make a donation 🍻](https://www.paypal.me/bnomei/5).

## Migrating tag use from Kirby V2 to V3

The `type` attribute is a reserved keyword for Kirby Tags in Kirby CMS V3.
You need to replace `type` with `kind`.

```
// V2
(random: 5 type: alpha)
// V3
(random: 5 kind: alpha)
```

Also the Site Methods has been replaced with a Page Method.

> ATTENTION: Page method not working (yet). issue pending.

```php
// V2
echo $site->random('red, green, blue, black, white, yellow', 'pool', 3);

// V3
echo $page->random('red, green, blue, black, white, yellow', 'pool', 3);

```

## Usage

Random string using [Kirby Toolkit](https://getkirby.com/docs/toolkit/api/str/random) `str::random()` forwarding the type if any.

```
(random: 5) or (random: 5 kind:alpha)
```

Random positiv non-zero number with max value inclusive using PHP `random_int()` (or `rand()` in php5).

```
(random: number length: 128)
```

Any one value of a comma seperated list.

```
(random: apple, banana, coconut)
```

Any random pool of values picked from a comma seperated list with optional length.

```
(random: red, green, blue, black, white, yellow kind: pool)
or
(random: red, green, blue, black, white, yellow kind: pool length: 3)
```

A number between a min and max value inclusive using PHP `random_int()` (or `rand()` in php5).
```
(random: 41, 53 kind: between)
```

`Lorem Ipsum` text using a [generator](https://github.com/joshtronic/php-loremipsum).

```
(random: lorem length: 5) or (random: lorem kind: words length: 4)
(random: lorem kind: sentences length: 3)
(random: lorem kind: paragraphs length: 2)
(random: lorem kind: chars length: 140)
```

Token
```
(random: token kind: number upper length: 12)
```

The plugin also adds a `$page->random()` function to use in templates etc.

```php
// STRING
echo $page->random(23);

// NUMBER
echo $page->random([41, 53], 'between');

// POOL
// from a comma seperated string
echo $page->random('red, green, blue, black, white, yellow', 'pool', 3);
// or a php array
echo $page->random($myArray, 'pool', 3);

// LOREM
echo $page->random('lorem', 'paragraphs', 3);

// Token: upper, lower, numbers
echo $page->random('token', 'lower,numbers', 5); // d63jd
echo $page->random('token', 'lower,upper', 5); // GjHoL
```

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-random/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
