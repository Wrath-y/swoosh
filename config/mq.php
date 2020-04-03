<?php

return [
    'rabbitmq' => [
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
                    'q4' => [
                        'r1.*'
                    ],
                    'q5' => [
                        '#.r2'
                    ]
                ]
            ]
        ]
    ],
    'kafka' => [
        'topics' => [
            't1', 't2'
        ]
    ]
];