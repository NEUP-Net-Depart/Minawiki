<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;

class IndexController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function index(Request $request, $title = null)
    {
        $pages = Page::all();
        $path = collect([]);
        if ($title == null) {
            $current_page = $pages->where('id', 1)->first();
            $path->prepend($current_page);
        } else {
            //Check if title exists
            $current_page = $pages->where('title', $request->title)->first();
            if (!isset($current_page))
                abort(404);
            //Calculate path to root and reverse
            else {
                while (true) {
                    $path->prepend($current_page);
                    if ($current_page['id'] == 1)
                        break;
                    $current_page = $pages->where('id', $current_page['father_id'])->first();
                }
            }
        }

        if ($title == null)
            $current_page = $pages->where('id', 1)->first();
        else
            $current_page = $pages->where('title', $request->title)->first();

        //Calculate left div data
        if (boolval($current_page['is_folder'])) {
            //Get all children
            $left_data = collect($pages->where('father_id', $current_page['id'])->all());
            $left_data_page = $current_page;
        } else {
            //Get all siblings
            $left_data = collect($pages->where('father_id', $current_page['father_id'])->all());
            $left_data_page = $pages->where('id', $current_page['father_id'])->first();
        }
        //Todo

        if ($request->session()->has('user.id')) {
            return view('index', ['path' => $path, 'left_data' => $left_data, 'current_page' => $current_page, 'left_data_page' => $left_data_page,
                'uid' => $request->session()->get('user.id'), 'power' => $request->session()->get('user.power')]);
        } else {
            return view('index', ['path' => $path, 'left_data' => $left_data, 'current_page' => $current_page, 'left_data_page' => $left_data_page]);
        }
    }
}
