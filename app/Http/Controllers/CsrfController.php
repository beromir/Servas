<?php

namespace App\Http\Controllers;

class CsrfController extends Controller
{
    public function getCsrfToken(): string
    {
        return csrf_token();
    }
}
