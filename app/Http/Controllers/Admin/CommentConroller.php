<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentConroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::whereApproved(1)->latest()->paginate(20);

        return view('admin.comments.all', compact('comments'));
    }

    public function unapproved() {


        $comments = Comment::query();

        if($keyword = request('search')){
            $comments->where('comment', 'LIKE', "%{$keyword}%")->orwhereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            });
        }

        $comments = $comments->whereApproved(0)->latest()->paginate(20);
        return view('admin.comments.unapproved', compact('comments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update(['approved'=>1]);

        alert()->success('نظر مورد نظر تایید شد');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        alert()->success('نظر با موفقیت حذف شد');

        return back();
    }
}
