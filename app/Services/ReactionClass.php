<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionClass
{
    public function toggleReaction(Post $post, Request $request)
    {
        $user = Auth::user();

        $reaction = Reaction::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($reaction) {
            if ($reaction->type === $request->type) {
                $reaction->delete();
            } else {
                $reaction->update(['type'=>$request->type]);
            }
        } else {
            Reaction::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'type' => $request->type
            ]);
        }

        $likes = $post->likes()->count();
        $dislikes = $post->dislikes()->count();
        $comments = $post->comments()->count();

        return [$likes, $dislikes, $comments];
    }
}
