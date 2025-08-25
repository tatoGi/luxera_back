<?php

return [
    'id' => 5,
    'type' => 5,
    'folder' => 'routes',
    'name' => 'Hiking Routes Page',
    'slug' => 'routes',
    'has_posts' => true,
    'post_attributes' => [
        'translatable' => [
            'route_name' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Route Name',
                'placeholder' => 'Enter hiking route name'
            ],
            'slug' => [
                'type' => 'text',
                'required' => true,
                'label' => 'URL Slug',
                'placeholder' => 'route-url-slug'
            ],
            'description' => [
                'type' => 'textarea',
                'required' => true,
                'label' => 'Route Description',
                'placeholder' => 'Describe the hiking route, difficulty, highlights, etc.'
            ],
            'location' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Location/Starting Point',
                'placeholder' => 'Starting location or region'
            ]
        ],
        'non_translatable' => [
            'map_link' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Map Link',
                'placeholder' => 'https://maps.google.com/... or GPX file URL'
            ],
            'route_image' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Route Image',
                'accept' => 'image/*'
            ],
            'difficulty_level' => [
                'type' => 'select',
                'required' => true,
                'label' => 'Difficulty Level',
                'default' => 'moderate',
                'options' => [
                    'easy' => 'Easy',
                    'moderate' => 'Moderate',
                    'hard' => 'Hard',
                    'expert' => 'Expert'
                ]
            ],
            'duration_hours' => [
                'type' => 'number',
                'required' => false,
                'label' => 'Duration (Hours)',
                'placeholder' => '3.5'
            ],
            'distance_km' => [
                'type' => 'number',
                'required' => false,
                'label' => 'Distance (KM)',
                'placeholder' => '12.5'
            ],
            'elevation_gain' => [
                'type' => 'number',
                'required' => false,
                'label' => 'Elevation Gain (m)',
                'placeholder' => '800'
            ],
            'season' => [
                'type' => 'select',
                'required' => false,
                'label' => 'Best Season',
                'default' => 'all_year',
                'options' => [
                    'all_year' => 'All Year',
                    'spring' => 'Spring',
                    'summer' => 'Summer',
                    'autumn' => 'Autumn',
                    'winter' => 'Winter'
                ]
            ],
            'status' => [
                'type' => 'select',
                'required' => true,
                'label' => 'Status',
                'default' => 'active',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'maintenance' => 'Under Maintenance'
                ]
            ]
        ]
    ]
];