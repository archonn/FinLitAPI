<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Task;
use App\Todo;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'balance', 'expo', 'parent', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'expo'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    protected $attributes = [
        'parent' => '',
        'expo' => ''
    ];

    public function assignTasks()
    {
        $tasks = Task::all();
        foreach($tasks as $task)
        {
            $todo = new Todo;
            $todo->userID = $this->id;
            $todo->taskID = $task->id;
            $todo->status = 'ongoing';

            $todo->save();
        }
        return $this;
    }
}
