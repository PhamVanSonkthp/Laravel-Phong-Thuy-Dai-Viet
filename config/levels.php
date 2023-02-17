<?php


return [
    'access' => [

        'chat-list' => 'chat_list',
        'chat-add' => 'chat_add',
        'chat-edit' => 'chat_edit',
        'chat-delete' => 'chat_delete',

        'topic-list' => 'topic_list',
        'topic-add' => 'topic_add',
        'topic-edit' => 'topic_edit',
        'topic-delete' => 'topic_delete',

        'candidate-list' => 'candidate_list',
        'candidate-add' => 'candidate_add',
        'candidate-edit' => 'candidate_edit',
        'candidate-delete' => 'candidate_delete',

        'finder-list' => 'finder_list',
        'finder-add' => 'finder_add',
        'finder-edit' => 'finder_edit',
        'finder-delete' => 'finder_delete',

        'pair-list' => 'pair_list',
        'pair-add' => 'pair_add',
        'pair-edit' => 'pair_edit',
        'pair-delete' => 'pair_delete',

        'date-list' => 'date_list',
        'date-add' => 'date_add',
        'date-edit' => 'date_edit',
        'date-delete' => 'date_delete',

    ],
    'table_module'=>[
        'chat',
        'topic',
        'candidate',
        'finder',
        'pair',
        'date',
    ],

    'table_module_name'=>[
        'Chat',
        'Topic',
        'Thêm Candidates',
        'Tìm Finder',
        'Tạo Pair',
        'Tạo Date',
    ],

    'module_children'=>[
        'list',
        'add',
        'edit',
        'delete',
    ]
];
