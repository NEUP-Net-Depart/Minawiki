<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use App\Redirect;

class PageController extends Controller
{
    protected $reserved = [ 'auth', 'page' ];
    protected $restricted = [ '/', '\\', ':', '%', '&', '#', '=', '<', '>', '-', '*', '"', '\'' ];

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    private function isInString($haystack, $needles) {
        $result = false;
        foreach ($needles as $needle) {
            $result = $result || ( false !== strpos($haystack, $needle) );
        }
        return $result;
    }

    private function isEqualString($haystack, $needles) {
        $result = false;
        foreach ($needles as $needle) {
            $result = $result || ( false !== ($haystack == $needle) );
        }
        return $result;
    }

    /**
     * Return left nav bar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function index(Request $request, $title = null) {
        $pages = Page::all();
        $path = collect([]);
        if ($title == null) {
            $current_page = $pages->where('id', 1)->first();
            $path->prepend($current_page);
        } else {
            //Check if title exists
            if(Redirect::where('title', $title)->count() > 0)
                $title = Redirect::where('title', $title)->first()->destination;
            $current_page = $pages->where('title', $title)->first();
            if (!isset($current_page))
                abort(404);
        }
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

        if ($request->session()->has('user.id')) {
            return view('page-nav', ['left_data' => $left_data,  'current_page' => $current_page, 'left_data_page' => $left_data_page,
                'uid' => $request->session()->get('user.id'), 'power' => $request->session()->get('user.power')]);
        } else {
            return view('page-nav', ['left_data' => $left_data, 'current_page' => $current_page, 'left_data_page' => $left_data_page]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'father_id' => 'required',
        ]);
        //Check keywords in title
        if($this->isInString($request->title, $this->restricted))
            return json_encode([
                'result' => 'false',
                'msg' => 'restricted',
            ]);
        if($this->isEqualString($request->title, $this->reserved))
            return json_encode([
                'result' => 'false',
                'msg' => 'reserved',
            ]);
        if(Page::where('title', $request->title)->count() > 0)
            return json_encode([
                'result' => 'false',
                'msg' => 'page already exists',
            ]);
        Redirect::where('title', $request->title)->delete();

        $page = new Page();

        $page->father_id = intval($request->father_id);
        $page->title = $request->title;

        if(isset($request->is_folder))
            $page->is_folder = $request->is_folder;
        if(isset($request->allow_child_folder))
            $page->allow_child_folder = $request->allow_child_folder;
        if(isset($request->is_notice))
            $page->is_notice = $request->is_notice;
        if(isset($request->protect_children))
            $page->protect_children = $request->protect_children;
        if(isset($request->power))
            $page->power = $request->power;

        $page->save();

        return json_encode([
            'result' => 'true',
            'msg' => 'success'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);
        //Check keywords in title
        if($this->isInString($request->title, $this->restricted))
            return json_encode([
                'result' => 'false',
                'msg' => 'restricted',
            ]);
        if($this->isEqualString($request->title, $this->reserved))
            return json_encode([
                'result' => 'false',
                'msg' => 'reserved',
            ]);
        if(Page::where('title', $request->title)->count() > 0)
            if(Page::where('title', $request->title)->first()->id != $id)
                return json_encode([
                    'result' => 'false',
                    'msg' => 'page already exists',
                ]);
        Redirect::where('title', $request->title)->delete();

        $page = Page::where('id', $id)->first();

        if($request->title != $page->title) {
            $redirect = new Redirect();
            $redirect->title = $page->title;
            $redirect->destination = $request->title;
            $redirect->save();
        }

        $page->title = $request->title;

        if(isset($request->is_folder))
            $page->is_folder = $request->is_folder;
        if(isset($request->allow_child_folder))
            $page->allow_child_folder = $request->allow_child_folder;
        if(isset($request->is_notice))
            $page->is_notice = $request->is_notice;
        if(isset($request->protect_children))
            $page->protect_children = $request->protect_children;
        if(isset($request->power))
            $page->power = $request->power;

        $page->save();

        return json_encode([
            'result' => 'true',
            'msg' => 'success'
        ]);
    }

    function move(Request $request, $id) {
        $this->validate($request, [
            'father_title' => 'required',
        ]);
        if(Page::where('title', $request->father_title)->count() == 0)
            return json_encode([
                'result' => 'false',
                'msg' => 'father not exist',
            ]);
        else {
            $pages = Page::all();
            $current_page = $pages->where('title', $request->father_title)->first();
            while (true) {
                if($current_page->id == $id)
                    return json_encode([
                        'result' => 'false',
                        'msg' => 'improper father',
                    ]);
                if ($current_page['id'] == 1)
                    break;
                $current_page = $pages->where('id', $current_page['father_id'])->first();
            }

            $father_id = Page::where('title', $request->father_title)->first()->id;
            $page = Page::where('id', $id)->first();
            $page->father_id = $father_id;
            $page->save();
            return json_encode([
                'result' => 'true',
                'msg' => 'success'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::where('id', $id)->delete();
        return json_encode([
            'result' => 'true',
            'msg' => 'success'
        ]);
    }
}
