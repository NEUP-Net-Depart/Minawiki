<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use App\Redirect;

class PageController extends Controller
{
    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    private function isInString($haystack, $needle) {
        return false !== strpos($haystack, $needle);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function addPage(Request $request)
    {
        //Check keywords in title
        if($this->isInString($request->title, '/') || $this->isInString($request->title, 'auth/'))
            return json_encode([
                'result' => 'false',
                'msg' => 'improper words in title',
            ]);
        if(Page::where('title', $request->title)->count() > 0)
            return json_encode([
                'result' => 'false',
                'msg' => 'page already exists',
            ]);
        Page::create(['father_id' => intval($request->father_id),
            'title' => $request->title,
            'is_folder' => boolval($request->is_folder)]);
        return json_encode([
            'result' => 'true',
            'msg' => 'success',
        ]);
    }
}
