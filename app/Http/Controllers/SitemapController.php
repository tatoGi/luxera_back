<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;

class SitemapController extends Controller
{
    public function generate()
    {
        // Specify the path where you want to save the sitemap file
        $path = base_path('sitemap.xml'); // You can change the path as per your requirement

        // Generate the sitemap for the specified domain and save it to the specified path
        SitemapGenerator::create('https://gametech.ge')->writeToFile($path);

        // Return the sitemap file as a view
        return response()->file($path);
    }
}
