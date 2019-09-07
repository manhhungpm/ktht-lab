<?php
return [
    'list' => [
        'title' => 'Báo cáo danh sách Brandname',
        'table' => [
            'brandname' => 'Brandname',
            'type' => 'Loại',
            'status' => 'Trạng thái',
            'reason' => 'Lí do',
            'who_created' => 'Người tạo',
            'when_created' => 'Thời gian tạo'
        ]
    ],
    'report' => [
        'day' => [
            'alias' => [
                'title' => 'Báo cáo sản lượng tin nhắn theo alias',
                'table' => [
                    'date' => "Ngày",
                    'type' => "Loại",
                    'alias' => "Alias",
                    'msg_count' => "Số TN/ngày/TB",
                    'msg_total' => "Tổng số TN",
                    'sub_count' => "Số TB"
                ],
                'filter' => [
                    'from' => 'Từ',
                    'to' => 'Đến',
                    'month' => 'Tháng'
                ]
            ],
            'segment' => [
                'title' => 'Báo cáo theo phân khúc khách hàng',
                'table' => [
                    'date' => "Ngày",
                    'type' => "Loại",
                    'amount' => "Số tin nhắn nhận được",
                    'vip' => "Số tin nhắn khách hàng VIP nhận được",
                    'potential' => "Số tin nhắn khách hàng tiềm năng nhận được",
                    'normal' => "Số tin nhắn khách hàng đại trà nhận được",
                    'total' => "Tổng"
                ],
                'filter' => [
                    'from' => 'Từ',
                    'to' => 'Đến'
                ]
            ]
        ],
        'accumulate' => [
            'segment' => [
                'title' => 'Báo cáo theo phân khúc khách hàng',
                'table' => [
                    'date' => "Ngày",
                    'type' => "Loại",
                    'amount' => "Số tin nhắn nhận được",
                    'vip' => "Số tin nhắn khách hàng VIP nhận được",
                    'potential' => "Số tin nhắn khách hàng tiềm năng nhận được",
                    'normal' => "Số tin nhắn khách hàng đại trà nhận được",
                    'total' => "Tổng"
                ],
                'filter' => [
                    'from' => 'Từ',
                    'to' => 'Đến'
                ]
            ],
            'alias' => [
                'title' => 'Báo cáo lũy kế theo alias'
            ]
        ],
        'month' => [
            'segment' => [
                'title' => 'Báo cáo theo tháng',
                'table' => [
                    'date' => "Ngày",
                    'type' => "Loại",
                    'amount' => "Số tin nhắn nhận được",
                    'vip' => "Số tin nhắn khách hàng VIP nhận được",
                    'potential' => "Số tin nhắn khách hàng tiềm năng nhận được",
                    'normal' => "Số tin nhắn khách hàng đại trà nhận được",
                    'total' => "Tổng"
                ],
                'filter' => [
                    'from' => 'Từ',
                    'to' => 'Đến'
                ]
            ]
        ],
        'duplicate' => [
            'alias' => [
                'title_day' => 'Báo cáo tin trùng trong 24h theo alias',
                'title_month' => 'Báo cáo tin trùng trong 30 ngày theo alias',
                'table' => [
                    'date' => "Ngày",
                    'type' => "Loại",
                    'alias' => "Alias",
                    'msg_count' => "Số TN/ngày/TB",
                    'msg_total' => "Tổng số TN",
                    'sub_count' => "Số TB"
                ],
                'filter' => [
                    'from' => 'Từ',
                    'to' => 'Đến'
                ]
            ],
            'segment' => [
                'title_day' => 'Báo cáo tin trùng trong 24h theo phân khúc',
                'title_month' => 'Báo cáo tin trùng trong 30 ngày theo phân khúc',
                'table' => [
                    'date' => "Ngày",
                    'type' => "Loại",
                    'amount' => "Số tin nhắn nhận được",
                    'vip' => "Số tin nhắn khách hàng VIP nhận được",
                    'potential' => "Số tin nhắn khách hàng tiềm năng nhận được",
                    'normal' => "Số tin nhắn khách hàng đại trà nhận được",
                    'total' => "Tổng"
                ],
                'filter' => [
                    'from' => 'Từ',
                    'to' => 'Đến'
                ]
            ]
        ],
        'config' => [

        ]
    ],
    'config' => [
        'segment_config' => [
            'unique' => 'Trong khoảng thời gian này, đã tồn tại bản ghi có phân khúc và loại tin này'
        ],
        'duplicate_config' => [
            'to_value_greater_from_value' => 'Giá trị bắt đầu phải nhỏ hơn giá trị đến',
            'unique' => 'Trong khoảng thời gian này, đã tồn tại bản ghi có phân khúc và loại tin này'
        ],
        'segment_warning_config' => [
            'to_value_greater_from_value' => 'Giá trị bắt đầu phải nhỏ hơn giá trị đến',
            'unique' => 'Trong khoảng thời gian này, đã tồn tại bản ghi có phân khúc và loại tin này',
            'danger_warning_from_is_greater_high_warning_to_per_day' => 'Mức bắt đầu của cảnh báo nguy hiểm trên ngày phải lớn hơn mức đến của cảnh báo cao trên ngày',
            'crisis_warning_from_is_greater_danger_warning_to_per_day' => 'Mức bắt đầu của cảnh báo khủng hoảng trên ngày phải lớn hơn mức đến của cảnh báo nguy hiểm trên ngày',
            'danger_warning_from_is_greater_high_warning_to_per_month' => 'Mức bắt đầu của cảnh báo nguy hiểm trên tháng phải lớn hơn mức đến của cảnh báo cao trên tháng',
            'crisis_warning_from_is_greater_danger_warning_to_per_month' => 'Mức bắt đầu của cảnh báo khủng hoảng trên tháng phải lớn hơn mức đến của cảnh báo nguy hiểm trên tháng',
        ],
        'validate' => [
            'duplicate' => 'Loại tin có thời gian áp dụng và ngày đã tồn tại',
            'timeframe_error' => 'Khoảng thời gian bị chồng nhau'
        ]
    ]
];