<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/random', [
    'pageMethods' => [
        'random' => function ($random, string $type = null, int $length = null) {
            return \Bnomei\Random::generate($random, $type, $length);
        }
    ],
    'tags' => [
        'random' => [
            'attr' => [
                'generator', 'length'
            ],
            'html' => function ($tag) {
                return \Bnomei\Random::generate(
                    (string)$tag->value,
                    $tag->generator ? strval($tag->generator) : null,
                    $tag->length ? intval($tag->length) : null
                );
            }
        ]
    ]
]);
