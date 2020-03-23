<?php

return [
    'queues' => [
        'q1'
    ],
    'exchanges' => [
        'ex_fanout' => [
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
        ],
        'ex_topic' => [
            'type' => 'topic',
            'queues' => [
                'q3' => [
                    'r1.*'
                ],
                'q4' => [
                    '#.r2'
                ]
            ]
        ]
    ]
];