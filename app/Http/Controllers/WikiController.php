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
        return view('history', ['paginator' => $versions]);
    }

    /**
     * Get single version ajax
     * @param Request $request
     * @param $title
     * @param $id
     * @return string
     */
    public function getOneVersion(Request $request, $title, $id)
    {
        $version = Version::where('id', $id)->first();
        if (empty($version))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid version id'
            ));
        else
            return json_encode(array(
                'result' => 'true',
                'content' => $version->content
            ));
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
        if (intval($request->session()->get('user.power')) < intval($page->power))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'permission denied'
            ));
        $number = 1;    //next number
        if ($page->versions()->count() > 0)
            $number = intval($page->versions()->get()->sortBy('number')->last()['number']) + 1;
        $parsedown = new Parsedown();
        $version = new Version;
        $version->number = $number;
        $version->content = clean($parsedown->text($request->text));
        $version->original = $request->text;
        if (!isset($request->message))
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

    /**
     * Restore a page to a specific version
     * @param Request $request
     * @param $title
     * @param $id
     * @return string
     */
    public function restore(Request $request, $title, $id)
    {
        $page = Page::where('title', $title)->first();
        if (empty($page))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid title'
            ));
        if (intval($request->session()->get('user.power')) < intval($page->power))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'permission denied'
            ));
        $current_last_version = $page->versions()->get()->sortBy('number')->last();
        if ($id != $current_last_version['id']) {
            $targetVersion = Version::where('id', $id)->first();
            if (empty($targetVersion))
                return json_encode(array(
                    'result' => 'false',
                    'msg' => 'invalid version id'
                ));
            $number = 1;    //next number
            if ($page->versions()->count() > 0)
                $number = intval($current_last_version['number']) + 1;
            $version = new Version;
            $version->number = $number;
            $version->content = $targetVersion->content;
            $version->original = $targetVersion->original;
            $version->message = "回档到版本 #" . strval($targetVersion->number) . ".";
            $version->user_id = $request->session()->get('user.id');
            $version->is_little = $targetVersion->is_little;
            $page->versions()->save($version);
            return json_encode(array(
                'result' => 'true',
                'msg' => 'success',
                'version' => $version
            ));
        } else
            return json_encode(array(
                'result' => 'true',
                'msg' => 'success',
                'version' => $current_last_version
            ));
    }
}
