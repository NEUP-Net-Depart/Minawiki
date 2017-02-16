<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use App\Redirect;
use App\Version;

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
            if (Redirect::where('title', $title)->count() > 0)
                return redirect('/' . Redirect::where('title', $title)->first()->destination);
            $current_page = $pages->where('title', $title)->first();
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

        if (boolval($current_page['is_folder'])) {
            $left_data_page = $current_page;
        } else {
            $left_data_page = $pages->where('id', $current_page['father_id'])->first();
        }

        $page_content = $current_page->versions->sortBy('number')->last();

        if ($request->session()->has('user.id')) {
            return view('index', ['path' => $path, 'current_page' => $current_page, 'left_data_page' => $left_data_page, 'content' => $page_content,
                'uid' => $request->session()->get('user.id'), 'power' => $request->session()->get('user.power'),
                'continue' => $request->getRequestUri()]);
        } else {
            return view('index', ['path' => $path, 'current_page' => $current_page, 'left_data_page' => $left_data_page, 'content' => $page_content,
                'continue' => $request->getRequestUri()]);
        }
    }
}
