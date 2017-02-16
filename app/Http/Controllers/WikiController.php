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
     * Get all versions
     * @param Request $request
     * @param $title
     */
    public function history(Request $request, $title)
    {
        $page = Page::where('title', $title)->first();
        if (empty($page))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid title'
            ));
        $versions = Version::where('page_id', $page->id)->orderBy('number', 'desc')->paginate(10);
        return view('history', [ 'paginator' => $versions ]);
        /*return json_encode(array(
            'result' => 'true',
            'history' => $versions
        ));*/
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
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid title'
            ));
        $number = 1;    //next number
        if ($page->versions()->count() > 0)
            $number = intval($page->versions()->get()->sortBy('number')->last()['number']) + 1;
        $parsedown = new Parsedown();
        $version = new Version;
        $version->number = $number;
        $version->content = clean($parsedown->text($request->text));
        $version->original = $request->text;
        if(!isset($request->message))
            $version->message = "修改了「" . $page->title . "」.";
        else
            $version->message = $request->message;
        $version->user_id = $request->session()->get('user.id');
        $version->is_little = boolval($request->is_little);
        $page->versions()->save($version);
        return json_encode(array(
            'result' => 'true',
            'msg' => 'success',
            'version' => $version
        ));
    }
}
