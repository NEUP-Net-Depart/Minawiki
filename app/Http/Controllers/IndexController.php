<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class IndexController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function index(Request $request)
    {
        if($request->session()->has('user.id'))
        {
            return view('index', [ 'uid' => $request->session()->get('user.id') ]);
        }
        else
        {
            return view('index');
        }
    }
}
