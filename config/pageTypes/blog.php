<?php

return [
    'id' => 2,
    'type' => 2,
    'name' => 'Blog Page',
    'slug' => 'blog',
    'has_posts' => true,
    'post_attributes' => [
        'translatable' => [
            'title' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Post Title',
                'placeholder' => 'Enter blog post title'
            ],
            'content' => [
                'type' => 'editor',
                'required' => true,
                'label' => 'Post Content',
                'placeholder' => 'Write your blog post content here'
            ],
            'slug' => [
                'type' => 'text',
                'required' => false,
                'label' => 'URL Slug',
                'placeholder' => 'post-url-slug (auto-generated if empty)'
            ],
           
            'meta_title' => [
                'type' => 'text',
                'required' => false,
                'label' => 'SEO Title',
                'placeholder' => 'SEO optimized title'
            ],
            'meta_description' => [
                'type' => 'textarea',
                'required' => false,
                'label' => 'SEO Description',
                'placeholder' => 'SEO meta description'
            ]
        ],
        'non_translatable' => [
            'featured_image' => [
                'type' => 'image',
                'required' => true,
                'label' => 'Featured Image',
                'accept' => 'image/*',
                'help' => 'Main image displayed in blog post and listings'
            ],
          
            'published_at' => [
                'type' => 'datetime-local',
                'required' => true,
                'label' => 'Publish Date',
                'default' => 'now',
                'help' => 'Date when the post should be published'
            ],
            'author' => [
                'type' => 'text',
                'required' => false,
                'label' => 'Author Name',
                'placeholder' => 'Post author (optional)'
            ],
           
            'status' => [
                'type' => 'select',
                'required' => true,
                'label' => 'Status',
                'default' => 'draft',
                'options' => [
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived'
                ]
            ]
        ]
    ]
];