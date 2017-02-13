<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function index(Request $request)
    {
        if($request->session()->has('user_id'))
        {
            return view('index', [ 'uid' => $request->session()->get('user_id') ]);
        }
        else
        {
            return view('index');
        }
    }
}
