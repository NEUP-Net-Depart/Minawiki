<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\CommentMessage;
use App\Star;
use App\StarMessage;
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
            $comments = Comment::with('replyTarget', 'stars')->where('page_id', $page->id)->orderBy('id', 'desc')->paginate(10);
        else if ($request->order == "mostpopular")
            $comments = Comment::with('replyTarget', 'stars')->where('page_id', $page->id)
                ->where('star_num', '>=', 10)
                ->orderBy('star_num', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(10);
        foreach ($comments as $comment) {
            if ($request->session()->has('user.id'))
                $comment->user_star_num = Star::where('comment_id', $comment->id)->where('user_id', $request->session()->get('user.id'))->count();
        }
        if ($request->session()->has('user.id'))
            return view('comment', ['paginator' => $comments, 'order' => $request->order,
                'power' => $request->session()->get('user.power'), 'uid' => $request->session()->get('user.id')
            ]);
        else
            return view('comment', ['paginator' => $comments, 'order' => $request->order, 'continue' => '/' . $title]);
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
        //Add comment
        $comment = new Comment();
        $comment->page_id = $page->id;
        $comment->user_id = $request->session()->get('user.id');
        $comment->content = clean($parsedown->text($request->text), $clean_config);
        $comment->signature = "匿名用户";
        $comment->position = "打酱油评论";
        $comment->ban = false;
        $comment->star_num = 0;

        if (isset($request->signature))
            $comment->signature = $request->signature;

        if (isset($request->reply_id)) {
            $target_comment = Comment::where('id', $request->reply_id)->first();
            if (empty($target_comment))
                return json_encode(array(
                    'result' => 'false',
                    'msg' => 'invalid reply id'
                ));
            $comment->reply_id = $target_comment->id;
            $comment->save();
            //Add comment message
            $comment_message = new CommentMessage;
            $comment_message->comment_id = $comment->id;
            $comment_message->user_id = $target_comment->user_id;
            $comment_message->is_read = false;
            $comment_message->save();
        } else
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
        if (empty($comment))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid comment id'
            ));
        //Check if it's deleting by itself
        if (intval($request->session()->get('user.id')) == intval($comment->user_id)) {
            //Delete stars
            Star::where('comment_id', $comment->id)->delete();
            //Delete star message
            StarMessage::where('comment_id', $comment->id)->delete();
            //Delete comment message
            CommentMessage::where('comment_id', $comment->id)->delete();
            //Delete comment
            Comment::where('id', $id)->delete();
            return json_encode(array(
                'result' => 'true',
                'msg' => 'delete success'
            ));
        }
        //Check power
        if ($request->session()->has('user.power') && intval($request->session()->get('user.power')) > 0) {
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
            'msg' => 'unauthorized'
        ));
    }

    /**
     * @param Request $request
     * @param $title
     * @param $id
     * @return string
     */
    public function star(Request $request, $title, $id)
    {
        $page = Page::where('title', $title)->first();
        if (empty($page))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid title'
            ));
        $comment = Comment::where('id', $id)->first();
        if (empty($comment))
            return json_encode(array(
                'result' => 'false',
                'msg' => 'invalid comment id'
            ));
        //Check star numbers
        switch ($comment->stars->where('user_id', $request->session()->get('user.id'))->count()) {
            case 0:
                //Add star
                $star = new Star;
                $star->user_id = intval($request->session()->get('user.id'));
                $comment->stars()->save($star);
                //Add star message
                $star_message = new StarMessage;
                $star_message->user_id = intval($request->session()->get('user.id'));
                $star_message->comment_id = $comment->id;
                $star_message->times = 1;
                $star_message->is_read = false;
                $star_message->save();
                //Update comment star count
                $comment->star_num = $comment->star_num + 1;
                $comment->save();
                $msg = 1;
                break;
            case 1:
                //Add star
                $star = new Star;
                $star->user_id = intval($request->session()->get('user.id'));
                $comment->stars()->save($star);
                //Update star message
                $star_message = StarMessage::where('user_id', $request->session()->get('user.id'))
                    ->where('comment_id', $comment->id)->first();
                $star_message->times = 2;
                $star_message->is_read = false;
                $star_message->save();
                //Update comment star count
                $comment->star_num = $comment->star_num + 1;
                $comment->save();
                $msg = 2;
                break;
            case 2:
                //Delete stars
                Star::where('user_id', $request->session()->get('user.id'))
                    ->where('comment_id', $comment->id)
                    ->delete();
                //Delete star message
                StarMessage::where('user_id', $request->session()->get('user.id'))
                    ->where('comment_id', $comment->id)
                    ->delete();
                //Update comment star count
                $comment->star_num = $comment->star_num - 2;
                $comment->save();
                $msg = 3;
                break;
        }
        return json_encode([
            'result' => 'true',
            'msg' => $msg
        ]);
    }
}
