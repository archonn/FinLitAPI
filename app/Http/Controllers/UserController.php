<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NotifyTransaction;
use App\User;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;

        $user->name = request()->name;
        $user->type = request()->type;
        $user->address = bin2hex(random_bytes(18));
        $user->balance = 100;

        $user->save();

        //assign weekly tasks to child
        if($user->type == 'child')
        {
            $user->assignTasks();
        }

        return response($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response($user, 200)->header('Content-type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $user->name = request()->name;
        $user->expo = request()->expo;
        $user->parent = request()->parent;
        $user->save();

        return response($user, 200)->header('Content-type', 'application/json');
    }
}
