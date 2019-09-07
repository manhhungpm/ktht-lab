<?php
return [
    'list' => [
        'title' => 'Brandname report',
        'table' => [
            'brandname' => 'Brandname',
            'type' => 'Type',
            'status' => 'Status',
            'reason' => 'Reason',
            'who_created' => 'Who created',
            'when_created' => 'When created'
        ]
    ],
    'report' => [
        'day' => [
            'alias' => [
                'title' => 'Messages count per alias report',
                'table' => [
                    'date' => "Date",
                    'type' => "Type",
                    'alias' => "Alias",
                    'msg_count' => "Messages count/month/subscribers",
                    'msg_total' => "Messages total",
                    'sub_count' => "Subscribers count"
                ],
                'filter' => [
                    'from' => 'From',
                    'to' => 'To',
                    'month' => 'Month'
                ]
            ],
            'segment' => [
                'title' => 'Segment report',
                'table' => [
                    'date' => "Date",
                    'type' => "Type",
                    'amount' => "Amount",
                    'vip' => "VIP",
                    'potential' => "Potential",
                    'normal' => "Normal",
                    'total' => "Total"
                ],
                'filter' => [
                    'from' => 'From',
                    'to' => 'To'
                ]
            ]
        ],
        'accumulate' => [
            'segment' => [
                'title' => 'Segment report',
                'table' => [
                    'date' => "Date",
                    'type' => "Type",
                    'amount' => "Amount",
                    'vip' => "VIP",
                    'potential' => "Potential",
                    'normal' => "Normal",
                    'total' => "Total"
                ],
                'filter' => [
                    'from' => 'From',
                    'to' => 'To'
                ]
            ],
            'alias' => [
                'title' => 'Alias report'
            ]
        ],
        'month' => [
            'segment' => [
                'title' => 'Segment report',
                'table' => [
                    'date' => "Date",
                    'type' => "Type",
                    'amount' => "Amount",
                    'vip' => "VIP",
                    'potential' => "Potential",
                    'normal' => "Normal",
                    'total' => "Total"
                ],
                'filter' => [
                    'from' => 'From',
                    'to' => 'To'
                ]
            ]
        ],
        'duplicate' => [
            'alias' => [
                'title_day' => 'Alias 24h report',
                'title_month' => 'Alias 30 days report',
                'table' => [
                    'date' => "Date",
                    'type' => "Type",
                    'alias' => "Alias",
                    'msg_count' => "Messages count/month/subscribers",
                    'msg_total' => "Messages total",
                    'sub_count' => "Subscribers count"
                ],
                'filter' => [
                    'from' => 'From',
                    'to' => 'To'
                ]
            ],
            'segment' => [
                'title_day' => 'Segment 24h report',
                'title_month' => 'Segment 30 days report',
                'table' => [
                    'date' => "Date",
                    'type' => "Type",
                    'amount' => "Amount",
                    'vip' => "VIP",
                    'potential' => "Potential",
                    'normal' => "Normal",
                    'total' => "Total"
                ],
                'filter' => [
                    'from' => 'From',
                    'to' => 'To'
                ]
            ]
        ],
        'config' => [

        ]
    ],
    'config' => [
        'segment_config' => [
            'unique' => 'A record with this sms type and segment already existed in this time range'
        ],
        'duplicate_config' => [
            'to_value_greater_from_value' => 'To value is greater from value',
            'unique' => 'A record with this sms type and segment already existed in this time range'
        ],
        'segment_warning_config' => [
            'to_value_greater_from_value' => 'To value is greater from value',
            'unique' => 'A record with this sms type already existed in this time range',
            'danger_warning_from_is_greater_high_warning_to_per_day' => 'Danger warning from per day value must be greater high warning to per day value',
            'crisis_warning_from_is_greater_danger_warning_to_per_day' => 'Crisis warning from per day value must be greater danger warning to per day value',
            'danger_warning_from_is_greater_high_warning_to_per_month' => 'Danger warning from per month value must be greater high warning to per month value',
            'crisis_warning_from_is_greater_danger_warning_to_per_month' => 'Crisis warning from per month value must be greater danger warning to per month value',
        ],
        'validate' => [
            'duplicate' => 'Duplicate sms config',
            'timeframe_error' => 'Time frame error'
        ]
    ]
];