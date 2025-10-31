<?php

namespace App\Http\Controllers;

use App\Events\PostUpdated;
use App\Models\Post;
use App\Services\ReactionClass;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function __construct(protected ReactionClass $reactionClass){}
    public function toggle(Request $request, Post $post)
    {
        $request->validated();

        [$likes, $dislikes, $comments] = $this->reactionClass->toggleReaction($post, $request->type);

        broadcast(new PostUpdated($post->id, $likes, $dislikes, $comments))->toOthers();

        return response()->json([
            'likes' => $likes,
            'dislikes' => $dislikes,
            'comments' => $comments
        ]);
    }
}
