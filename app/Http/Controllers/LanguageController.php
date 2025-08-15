<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            Session::put('locale', $locale);
        }
        return redirect()->back();
    }
}