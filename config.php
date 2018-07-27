<?php
    Kirby::plugin('bnomei/random', [
        'pageMethods' => [
            'random' => function ($random, $type = false, $length = false) {
                if (gettype($random) == 'string') {
                    $random = explode(',', str_replace(', ', ',', $random));
                } elseif (gettype($random) == 'integer') {
                    $random = array($random);
                }

                return \Bnomei\Random::random($random, $type, $length, 'site::method');
            }
        ],
        'tags' => [
            'random' => [
                'attr'=> [
                    'kind', 'length'
                ],
                'html' => function ($tag) {
                    $random = explode(',', str_replace(', ', ',', (string)$tag->value));
                    $type = $tag->kind;
                    $length = $tag->length ? intval($tag->length) : false;

                    return \Bnomei\Random::random($random, $type, $length, 'tag');
                }
            ]
        ]
    ]);
