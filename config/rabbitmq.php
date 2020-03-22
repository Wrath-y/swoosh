<?php

return [
    'queues' => [
        'q1'
    ],
    'exchanges' => [
        'ex_anout' => [
            'type' => 'fanout'
        ],
        'ex_direct' => [
            'type' => 'direct',
            'queues' => [
                'q2' => [
                    'r1'
                ],
                'q3' => [
                    'r2'
                ]
            ]
        ]
    ]
];