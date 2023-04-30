<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly UserRepository $userRepository, private readonly UserService $userService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'users' => $this->userRepository->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ];

        $this->userService->create($request->validate($rules));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $closure = $this->userRepository->findWhere(function (Builder $builder)use($id){
            $builder->where('id', $id);
        });

        $array = $this->userRepository->findWhere(['id' => $id]);

        dd($closure, $array);

        return response()->json([
            $this->userRepository->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [];

        $this->userService->update($id, $request->validate($rules));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userService->delete($id);
    }
}
