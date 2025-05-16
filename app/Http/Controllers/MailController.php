<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\Mail\OrderMail;

class MailController extends Controller
{
    public function index()
    {
        Mail::to('nosoyflix@gmail.com')->send(new TestMail([
            'title' => 'Test',
            'body' => 'Testeando el email',
        ]));
    }
}
