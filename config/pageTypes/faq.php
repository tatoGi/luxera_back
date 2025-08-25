<?php

return [
    'id' => 4,
    'type' => 4,
    'folder' => 'sale',
    'name' => 'FAQ Page',
    'slug' => 'faq',
    'has_posts' => true,
    'post_attributes' => [
        'translatable' => [
            'question' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Question',
                'placeholder' => 'Enter the frequently asked question'
            ],
            'answer' => [
                'type' => 'editor',
                'required' => true,
                'label' => 'Answer',
                'placeholder' => 'Provide a detailed answer'
            ]
        ],
        'non_translatable' => [
            'order' => [
                'type' => 'number',
                'required' => false,
                'label' => 'Display Order',
                'placeholder' => '1',
                'default' => 0
            ],
            'category' => [
                'type' => 'select',
                'required' => true,
                'label' => 'FAQ Category',
                'default' => 'CLIENT',
                'options' => [
                    'CLIENT' => 'Client Questions',
                    'ADMIN' => 'Admin Questions',
                    'RETAILER' => 'Retailer Questions'
                ]
            ],
            'status' => [
                'type' => 'select',
                'required' => true,
                'label' => 'Status',
                'default' => 'active',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive'
                ]
            ]
        ]
    ]
];