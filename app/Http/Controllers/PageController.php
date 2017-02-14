<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use App\Redirect;

class PageController extends Controller
{
    /**
     * @param Request $request
     * @return Redirect
     */
    public function showPage(Request $request)
    {
        $pages = Page::all();
        $path = collect([]);
        //Check if title exists
        $current_page = $pages->where('title', $request->title)->first();
        if(!isset($current_page))
            return redirect('/');
        //Calculate path to root and reverse
        else {
            while ($current_page['id'] != 1)
            {
                $path->prepend($current_page['id']);
                $current_page = $pages->where('id', $current_page['father_id'])->first();
            }
            $path->prepend(1);
        }
        //Calculate left div data
        $current_page = $pages->where('title', $request->title)->first();
        if(boolval($current_page['is_folder']))
        {
            //Get all children
            $left_data = collect($pages->where('father_id', $current_page['id'])->all());
        }
        else
        {
            //Get all siblings
            $left_data = collect($pages->where('father_id', $current_page['father_id'])->all());
        }
        //Todo
    }

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
