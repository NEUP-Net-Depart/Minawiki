<?php

namespace App\Http\Controllers;

use Mews\Purifier\Purifier;
use Parsedown;
use Illuminate\Http\Request;
use App\Page;
use App\User;
use App\Version;

class WikiController extends Controller
{
    /**
     * @param Request $request
     * @param $title
     */
    public function index(Request $request, $title)
    {
        $page = Page::where('title', $title)->first();
        if (empty($page))
            return json_encode(array([
                'result' => 'false',
                'msg' => 'invalid title'
            ]));
        //Get newest version
        return json_encode(array([
            'result' => 'true',
            'content' => $page->versions()->get()->sortBy('number')->last()['content']
        ]));
    }

    /**
     * Get all versions
     * @param Request $request
     * @param $title
     */
    public function history(Request $request, $title)
    {
        $page = Page::where('title', $title)->first();
        if (empty($page))
            return json_encode(array([
                'result' => 'false',
                'msg' => 'invalid title'
            ]));
        return json_encode(array([
            'result' => 'true',
            'history' => $page->versions->sortBy('number')->toJson()
        ]));
    }

    /**
     * Save version
     * @param Request $request
     * @param $title
     */
    public function store(Request $request, $title)
    {
        $page = Page::where('title', $title)->first();
        if (empty($page))
            return json_encode(array([
                'result' => 'false',
                'msg' => 'invalid title'
            ]));
        $number = 1;    //next number
        if ($page->versions()->count() > 0)
            $number = intval($page->versions()->get()->sortBy('number')->last()['number']) + 1;
        $parsedown = new Parsedown();
        $version = new Version;
        $version->number = $number;
        $version->content = clean($parsedown->text($request->text));
        $version->user_id = $request->session()->get('user.id');
        $version->is_little = boolval($request->is_little);
        $page->versions()->save($version);
        return json_encode(array([
            'result' => 'true',
            'msg' => 'success',
            'number' => $number
        ]));
    }
}
