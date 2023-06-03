<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsrfController extends Controller
{
    public function getCsrfToken(): string
    {
        return csrf_token();
    }
}
