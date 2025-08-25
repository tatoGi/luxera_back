<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebUser; // Assuming your model name is WebUser
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class WebUserController extends Controller
{
    public function index(): Factory|View
    {
        $webUsers = WebUser::all(); // Fetch all web users from the database

        return view('admin.webuser.index', compact('webUsers'));
    }
}
