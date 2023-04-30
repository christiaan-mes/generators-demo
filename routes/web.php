<?php

use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    /** @var UserRepository $userRepository */
    $userRepository = app()->make(UserRepository::class);

    $users = $userRepository->get();

    dd($users);
});

Route::get('service/create', function () {
    /** @var \App\Contracts\ModelServiceContract $userService */
    $userService = app()->make(UserService::class);

    $users = \App\Models\User::factory(10)->make(['password' => \Illuminate\Support\Facades\Hash::make('test123')]);
    $userModels = [];

    /** @var \App\Models\User $user */
    foreach ($users as $user) {
        $userModels[] = $userService->create([
            'name' => $user->name,
            'email' => $user->email,
            'password ' => $user->password,
        ]);
    }

    dd($userModels);
});

Route::resource(name: 'users', controller: \App\Http\Controllers\UserController::class);
