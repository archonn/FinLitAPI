<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\Todo;
use App\User;
use App\Transaction;

use App\Notifications\NotifyTaskCompletion;

class TodoController extends Controller
{
    public function createTask(Request $request)
    {
    	$task = new Task;
    	$task->name = request()->name;
    	$task->description = request()->description;
    	$task->reward = request()->reward;

    	$task->save();

    	return response($task, 200)->header('Content-type', 'application/json');
    }

    public function showTodo($uid)
    {
        $user = User::where('address', $uid)->first();
        if($user->type == 'parent')
        {
            $children = User::where('parent', $user->address)->get();
            $todos = [];

            foreach($children as $c)
            {
                $todo = Todo::where('userID', $c->id)->get();

                foreach($todo as $t)
                {
                    $user = User::find($t->userID);
                    $t['userID'] = $user->name;

                    $task = Task::find($t->taskID);
                    $t['taskID'] = $task->name;
                    $t['icon'] = $task->icon;

                    array_push($todos, $t);
                }
            }

            return response($todos, 200)->header('Content-type', 'application/json');
        }
        else 
        {
            $todos = Todo::where('userID', $user->id)->get();
            return response($todos, 200)->header('Content-type', 'application/json');
        }
    }

    public function showTodoDetails($id)
    {
        $todo = Todo::find($id);
        $task = Task::find($todo->taskID)->first();
        $task['todo'] = $todo->id;

        return response($task, 200)->header('Content-type', 'application/json');
    }

    public function finishTodo()
    {
        $todo = Todo::find(request()->id);

        if($todo->status == 'ongoing')
        {
            $todo->status = 'finished';

            $receiver = User::find($todo->userID);
            $sender = User::find($todo->userID)->parent;
            $reward = Task::find($todo->taskID)->reward;

            $transaction = new Transaction;
            $transaction->from = $sender;
            $transaction->to = $receiver->address;
            $transaction->amount = $reward;
            $transaction->type = 'Reward';

            $balanceCheck = $transaction->checkBalance();

            if($balanceCheck)
            {
                $todo->save();
                $transaction->makeTransaction();
                $message = 'You have rewarded your child with '.$reward.' tokens.';
                $receiver->notify(new NotifyTaskCompletion($reward));    
            }
            else
            {
                $message = 'Not enough token to reward';
            }
        }
        else 
        {
            $message = 'The task is already finished';
        }

        return response(json_encode($message), 200)->header('Content-type', 'application/json');
    }
}
