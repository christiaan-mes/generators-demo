<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private readonly PostRepository $postRepository, private readonly PostService $postService)
    {
        //
    }

    public function index()
    {
        $posts = $this->postRepository->get();

        return response()->json([
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return view()->make('posts.create');
    }

    public function store(Request $request)
    {
        $rules = [];

        $this->postService->create($request->validate($rules));

        return redirect()->back();
    }

    public function show(string $id)
    {
        $post = $this->postRepository->findOrFail($id);

        return view()->make('posts.show', [
            'post' => $post
        ]);
    }

    public function edit(string $id)
    {
        $post = $this->postRepository->findOrFail($id);

        return view()->make('posts.edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request, string $id)
    {
        $rules = [];

        $this->postService->update($id, $request->validate($rules));

        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $this->postService->delete($id);

        return redirect()->back();
    }
}
