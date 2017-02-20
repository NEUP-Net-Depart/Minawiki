<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\CommentMessage;
use App\Page;
use Parsedown;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $title)
    {
        $page = Page::where('title', $title)->first();
        if (empty($page))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid title'
            ));
        if (!isset($request->order)) $request->order = "mostpopular";
        if ($request->order == "latest")
            $comments = Comment::with('replyTarget')->where('page_id', $page->id)->orderBy('id', 'desc')->paginate(10);
        else if ($request->order == "mostpopular")
            $comments = Comment::with('replyTarget')->where('page_id', $page->id)
                ->where('star_num', '>=', 10)
                ->orderBy('star_num', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(10);
        return view('comment', ['paginator' => $comments, 'order' => $request->order]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $title)
    {
        $page = Page::where('title', $title)->first();
        if (empty($page))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid title'
            ));

        $parsedown = new Parsedown();
        $clean_config = [
            'HTML.Doctype' => 'XHTML 1.0 Strict',
            'HTML.Allowed' => 'div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],del,code,q,blockquote,img[width|height|alt|src],table[summary],thead,tbody,tfoot,th[abbr|colspan|rowspan],tr,td[abbr|colspan|rowspan]',
            'CSS.AllowedProperties' => 'font-weight,font-style,font-family,text-decoration,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty' => true,
        ];
        $comment = new Comment();
        $comment->page_id = $page->id;
        $comment->user_id = $request->session()->get('user.id');
        $comment->content = clean($parsedown->text($request->text), $clean_config);
        $comment->signature = "匿名用户";
        $comment->position = "打酱油评论";
        $comment->ban = false;
        $comment->star_num = 0;

        if(isset($request->reply_id))
            $comment->reply_id = $request->reply_id;
        if(isset($request->signature))
            $comment->signature = $request->signature;

        $comment->save();

        return json_encode(array(
            'result' => 'true',
            'msg' => 'success',
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $title, $id)
    {
        $page = Page::where('title', $title)->first();
        if (empty($page))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid title'
            ));
        $comment = Comment::where('id', $id)->first();
        if(empty($comment))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid comment id'
            ));
        //Check if it's deleting by itself
        if(intval($request->session()->get('user.id')) == intval($comment->user_id))
        {
            Comment::where('id', $id)->delete();
            return json_encode(array(
                'result' => 'true',
                'msg' => 'delete success'
            ));
        }
        //Check power
        if($request->session()->has('user.power') && intval($request->session()->get('user.power')) > 0)
        {
            $comment->ban = true;
            $comment->ban_admin = strval($request->session()->get('user.admin'));
            $comment->save();
            return json_encode(array(
                'result' => 'true',
                'msg' => 'ban success'
            ));
        }
        return json_encode(array(
            'result' => 'false',
            'msg' => 'unauthorised'
        ));
    }
}
