<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Page;

class FixPostPageRelationships extends Command
{
    protected $signature = 'fix:post-page-relationships';
    protected $description = 'Fix missing post-page relationships';

    public function handle()
    {
        $this->info('Checking post-page relationships...');
        
        // Get posts without pages
        $postsWithoutPages = Post::whereNull('page_id')->get();
        $this->info("Found {$postsWithoutPages->count()} posts without pages");
        
        // Get all pages
        $pages = Page::all();
        $this->info("Found {$pages->count()} pages total");
        
        // Show current data
        $posts = Post::all();
        $this->info("\nCurrent Posts and their Pages:");
        $this->table(
            ['Post ID', 'Page ID', 'Direct Page Check', 'With Relationship'],
            $posts->map(function ($post) {
                // Direct page lookup
                $directPage = $post->page_id ? Page::find($post->page_id) : null;
                
                // Try to load relationship
                $post->load('page');
                
                return [
                    $post->id,
                    $post->page_id ?? 'NULL',
                    $directPage ? $directPage->title : 'Not Found',
                    $post->page ? $post->page->title : 'Relationship Failed'
                ];
            })
        );
        
        // Show pages with their attributes
        $this->info("\nAvailable Pages with Details:");
        $this->table(
            ['Page ID', 'Title', 'Type ID', 'Slug', 'Active', 'Created'],
            $pages->map(function ($page) {
                return [
                    $page->id,
                    $page->title ?? 'No Title',
                    $page->type_id ?? 'No Type',
                    $page->slug ?? 'No Slug',
                    $page->active ?? 'NULL',
                    $page->created_at ? $page->created_at->format('Y-m-d H:i') : 'NULL'
                ];
            })
        );
        
        // Test individual relationships
        $this->info("\nTesting Individual Post-Page Relationships:");
        foreach ($posts as $post) {
            if ($post->page_id) {
                $page = Page::find($post->page_id);
                $relationship = $post->page;
                
                $this->line("Post {$post->id} -> Page {$post->page_id}:");
                $this->line("  Direct lookup: " . ($page ? "✓ Found: {$page->title}" : "✗ Not found"));
                $this->line("  Relationship: " . ($relationship ? "✓ Found: {$relationship->title}" : "✗ Failed"));
            }
        }
        
        return 0;
    }
}
