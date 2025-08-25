<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();

        return view('admin.contacts.index', compact('contacts'));
    }
    public function subscribers()
    {
        $subscribers = Subscriber::all();

        return view('admin.subscribers.index', compact('subscribers'));
    }
}
