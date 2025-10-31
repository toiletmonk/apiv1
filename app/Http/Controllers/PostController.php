<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\PostIndexRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(PostIndexRequest $request)
    {
        $posts = $this->postService->getFilteredResults($request->validated());

        return PostResource::collection($posts);
    }

    public function store(CreatePostRequest $request)
    {
        $data = $request->validated();

        $post = Post::create($data);

        return response()->json(['message' => "Post $post->title created!"], 201);
    }

    public function show(Post $post)
    {
        return response()->json($post, 200);
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        $data = $request->validated();

        $this->authorize('update', $post);

        $post->update($data);

        return response()->json(['message' => "Post $post->title updated."], 200);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->noContent();
    }
}
