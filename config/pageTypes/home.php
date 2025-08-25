<?php

return [
    'id' => 1,
    'type' => 1,
    'name' => 'Home Page',
    'slug' => 'home',
    'has_posts' => true,
    
    // Section Types Configuration
    'section_types' => [
        'join_us' => [
            'label' => 'Join Us Section',
            'description' => 'გამოიმუშავე დამატებითი შემოსავალი მარტივად',
            'translatable_fields' => [
                'join_title_line_1' => [
                    'type' => 'text',
                    'required' => true,
                    'label' => 'Title Line 1',
                    'placeholder' => 'გამოიმუშავე დამატებითი'
                ],
                'join_title_line_2' => [
                    'type' => 'text',
                    'required' => true,
                    'label' => 'Title Line 2',
                    'placeholder' => 'შემოსავალი მარტივად'
                ],
                'join_description_1' => [
                    'type' => 'textarea',
                    'required' => true,
                    'label' => 'Description Paragraph 1',
                    'rows' => 3,
                    'placeholder' => 'მომთაბარე ონლაინ პლატფორმაა...'
                ],
                'join_description_2' => [
                    'type' => 'textarea',
                    'required' => false,
                    'label' => 'Description Paragraph 2',
                    'rows' => 3,
                    'placeholder' => 'არ აქვს მნიშვნელობა...'
                ],
                'join_description_3' => [
                    'type' => 'textarea',
                    'required' => false,
                    'label' => 'Description Paragraph 3',
                    'rows' => 3,
                    'placeholder' => 'შექმენი მაღაზია ახლავე...'
                ],
                'join_button_text' => [
                    'type' => 'text',
                    'required' => true,
                    'label' => 'Button Text',
                    'placeholder' => 'შემოგვიერთდი'
                ]
            ],
            'non_translatable_fields' => [
                'main_image' => [
                    'type' => 'image',
                    'required' => false,
                    'label' => 'Main Equipment Image'
                ],
                'helmet_image' => [
                    'type' => 'image',
                    'required' => false,
                    'label' => 'Helmet Image (Light Mode)'
                ],
                'helmet_image_dark' => [
                    'type' => 'image',
                    'required' => false,
                    'label' => 'Helmet Image (Dark Mode)'
                ],
                'snowboard_image' => [
                    'type' => 'image',
                    'required' => false,
                    'label' => 'Snowboard Image (Light Mode)'
                ],
                'snowboard_image_dark' => [
                    'type' => 'image',
                    'required' => false,
                    'label' => 'Snowboard Image (Dark Mode)'
                ],
                'button_url' => [
                    'type' => 'text',
                    'required' => false,
                    'label' => 'Button URL',
                    'placeholder' => '/register or #section'
                ]
            ]
        ],
        
        'rental_steps' => [
            'label' => 'Rental Steps',
            'description' => 'იქირავე შენთვის სასურველი აღჭურვილობა 3 მარტივი ნაბიჯით',
            'translatable_fields' => [
                'rental_title_line_1' => [
                    'type' => 'text',
                    'required' => true,
                    'label' => 'Title Line 1',
                    'placeholder' => 'იქირავე შენთვის სასურველი'
                ],
                'rental_title_line_2' => [
                    'type' => 'text',
                    'required' => true,
                    'label' => 'Title Line 2',
                    'placeholder' => 'აღჭურვილობა 3 მარტივი ნაბიჯით'
                ],
                'rental_main_description' => [
                    'type' => 'textarea',
                    'required' => true,
                    'label' => 'Main Description',
                    'rows' => 4,
                    'placeholder' => 'მომთაბარე საშუალებას გაძლევს...'
                ],
                'step_1_title' => [
                    'type' => 'text',
                    'required' => true,
                    'label' => 'Step 1 Title',
                    'placeholder' => 'შემოვიდი ანგარიში'
                ],
                'step_1_description' => [
                    'type' => 'textarea',
                    'required' => true,
                    'label' => 'Step 1 Description',
                    'rows' => 2,
                    'placeholder' => 'Step 1 details...'
                ],
                'step_2_title' => [
                    'type' => 'text',
                    'required' => true,
                    'label' => 'Step 2 Title',
                    'placeholder' => 'შეარჩიე სასურველი პროდუქტი'
                ],
                'step_2_description' => [
                    'type' => 'textarea',
                    'required' => true,
                    'label' => 'Step 2 Description',
                    'rows' => 2,
                    'placeholder' => 'Step 2 details...'
                ],
                'step_3_title' => [
                    'type' => 'text',
                    'required' => true,
                    'label' => 'Step 3 Title',
                    'placeholder' => 'გადახდისა ოფლაინ'
                ],
                'step_3_description' => [
                    'type' => 'textarea',
                    'required' => true,
                    'label' => 'Step 3 Description',
                    'rows' => 2,
                    'placeholder' => 'Step 3 details...'
                ]
            ],
            'non_translatable_fields' => [
                'rental_main_image' => [
                    'type' => 'image',
                    'required' => false,
                    'label' => 'Main Rental Steps Image'
                ],
                'step_1_icon' => [
                    'type' => 'image',
                    'required' => false,
                    'label' => 'Step 1 Icon'
                ],
                'step_2_icon' => [
                    'type' => 'image',
                    'required' => false,
                    'label' => 'Step 2 Icon'
                ],
                'step_3_icon' => [
                    'type' => 'image',
                    'required' => false,
                    'label' => 'Step 3 Icon'
                ]
            ]
        ]
    ],
    
    // Standard post_attributes format (converted from section_types)
    'post_attributes' => [
        'translatable' => [
            // Join Us Section fields
            'join_title_line_1' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Title Line 1',
                'placeholder' => 'გამოიმუშავე დამატებითი',
                'show_for_types' => ['join_us']
            ],
            'join_title_line_2' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Title Line 2',
                'placeholder' => 'შემოსავალი მარტივად',
                'show_for_types' => ['join_us']
            ],
            'join_description_1' => [
                'type' => 'textarea',
                'required' => true,
                'label' => 'Description Paragraph 1',
                'rows' => 3,
                'placeholder' => 'მომთაბარე ონლაინ პლატფორმაა...',
                'show_for_types' => ['join_us']
            ],
            'join_description_2' => [
                'type' => 'textarea',
                'required' => false,
                'label' => 'Description Paragraph 2',
                'rows' => 3,
                'placeholder' => 'არ აქვს მნიშვნელობა...',
                'show_for_types' => ['join_us']
            ],
            'join_description_3' => [
                'type' => 'textarea',
                'required' => false,
                'label' => 'Description Paragraph 3',
                'rows' => 3,
                'placeholder' => 'შექმენი მაღაზია ახლავე...',
                'show_for_types' => ['join_us']
            ],
            'join_button_text' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Button Text',
                'placeholder' => 'შემოგვიერთდი',
                'show_for_types' => ['join_us']
            ],
            
            // Rental Steps Section fields
            'rental_title_line_1' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Title Line 1',
                'placeholder' => 'იქირავე შენთვის სასურველი',
                'show_for_types' => ['rental_steps']
            ],
            'rental_title_line_2' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Title Line 2',
                'placeholder' => 'აღჭურვილობა 3 მარტივი ნაბიჯით',
                'show_for_types' => ['rental_steps']
            ],
            'rental_main_description' => [
                'type' => 'textarea',
                'required' => true,
                'label' => 'Main Description',
                'rows' => 4,
                'placeholder' => 'მომთაბარე საშუალებას გაძლევს...',
                'show_for_types' => ['rental_steps']
            ],
            'step_1_title' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Step 1 Title',
                'placeholder' => 'შემოვიდი ანგარიში',
                'show_for_types' => ['rental_steps']
            ],
            'step_1_description' => [
                'type' => 'textarea',
                'required' => true,
                'label' => 'Step 1 Description',
                'rows' => 2,
                'placeholder' => 'Step 1 details...',
                'show_for_types' => ['rental_steps']
            ],
            'step_2_title' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Step 2 Title',
                'placeholder' => 'შეარჩიე სასურველი პროდუქტი',
                'show_for_types' => ['rental_steps']
            ],
            'step_2_description' => [
                'type' => 'textarea',
                'required' => true,
                'label' => 'Step 2 Description',
                'rows' => 2,
                'placeholder' => 'Step 2 details...',
                'show_for_types' => ['rental_steps']
            ],
            'step_3_title' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Step 3 Title',
                'placeholder' => 'გადახდისა ოფლაინ',
                'show_for_types' => ['rental_steps']
            ],
            'step_3_description' => [
                'type' => 'textarea',
                'required' => true,
                'label' => 'Step 3 Description',
                'rows' => 2,
                'placeholder' => 'Step 3 details...',
                'show_for_types' => ['rental_steps']
            ]
        ],
        'non_translatable' => [
            // Common fields (always visible)
            'post_type' => [
                'type' => 'select',
                'required' => true,
                'label' => 'Post Type',
                'options' => [
                    'join_us' => 'Join Us Section',
                    'rental_steps' => 'Rental Steps'
                ],
                'default' => 'join_us'
            ],
            'sort_order' => [
                'type' => 'number',
                'required' => true,
                'label' => 'Sort Order',
                'default' => 1
            ],
            'status' => [
                'type' => 'select',
                'required' => true,
                'label' => 'Status',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive'
                ],
                'default' => 'active'
            ],
            
            // Join Us Section images
            'main_image' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Main Equipment Image',
                'show_for_types' => ['join_us']
            ],
            'helmet_image' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Helmet Image (Light Mode)',
                'show_for_types' => ['join_us']
            ],
            'helmet_image_dark' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Helmet Image (Dark Mode)',
                'show_for_types' => ['join_us']
            ],
            'snowboard_image' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Snowboard Image (Light Mode)',
                'show_for_types' => ['join_us']
            ],
            'snowboard_image_dark' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Snowboard Image (Dark Mode)',
                'show_for_types' => ['join_us']
            ],
            'button_url' => [
                'type' => 'text',
                'required' => false,
                'label' => 'Button URL',
                'placeholder' => '/register or #section',
                'show_for_types' => ['join_us']
            ],
            
            // Rental Steps Section images
            'rental_main_image' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Main Rental Steps Image',
                'show_for_types' => ['rental_steps']
            ],
            'step_1_icon' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Step 1 Icon',
                'show_for_types' => ['rental_steps']
            ],
            'step_2_icon' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Step 2 Icon',
                'show_for_types' => ['rental_steps']
            ],
            'step_3_icon' => [
                'type' => 'image',
                'required' => false,
                'label' => 'Step 3 Icon',
                'show_for_types' => ['rental_steps']
            ]
        ]
    ]
];