<?php

namespace App\Http\Controllers;

use App\CommentMessage;
use App\Star;
use App\StarMessage;
use App\User;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private function userId()
    {
        return session('user.id');
    }
    private function userCommentId()
    {
        $userId = $this->userId();
        $contentId = User::find($userId)->comments()->pluck('id');
        // dd($content_id);
        return $contentId;
    }

    private function userCommentContent(){
        $contentId = $this->userCommentId();
        $content=null;
        foreach ($contentId as $value)
        $content[$value] = Comment::where('id',$value)->pluck('content');
        //dd($content);
        return $content;
    }

    private function userCommentReply(){
        $commentId = $this->userCommentId();
        $reply = null;
        foreach ($commentId as $value)
            $reply[$value] =Comment::find($value)->replies()->pluck('content');
      //  dd($reply);
        return $reply;
    }

    private function userCommentReplyId(){
        $commentId = $this->userCommentId();
        $replyId = null;
        foreach ($commentId as $value)
            $replyId[$value] =Comment::find($value)->replies()->pluck('user_id');
       // dd($replyId);
        return $replyId;
    }

    private function userCommentStarNum()
    {
        $commentId = $this->userCommentId();
        $num = null;
        foreach ($commentId as $value)
            $num[$value]=Comment::where('id',$value)->value('star_num');
      //  dd($num);
        return $num;
    }

    private function userCommentStarUser()
    {
        $commentId = $this->userCommentId();
        $starUser=null;
        foreach ($commentId as $value)
        $starUser[$value] = Comment::find($value)->star_messages()->pluck('user_id');
      //  dd($starUser);
        return $starUser;
    }
    private function userMessage(){

        $message=array('commentId'=>$this->userCommentId(),'content'=>$this->userCommentContent(),
            'reply'=>$this->userCommentReply(), 'replyId'=>$this->userCommentReplyId(),
            'starNum'=>$this->userCommentStarNum(),'starUser'=>$this->userCommentStarUser());
        //dd($message);
        return $message;
    }
    public function visitUser(){
        $message=$this->userMessage();
       return view('user',compact('message'));
    }

}
