<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
   public function index()
   {
    $tasks = Task::all();
    return Inertia::render('TodoList.vue',['tasks' =>$tasks]);
   }

   public function store(Request $request)
   {
    $request->validate(['title' =>'required|string|max:255']);
    Task::create($request->only('title'));
    return redirect()->back();
   }


   // public function update(Request $request, Task $task)
   // {
   //  $task->update(['completed' => $request->completed]);
   //  return redirect()->back();
   // }

   // public function destroy(Task $task)
   // {
   //  $task->delete();
   //  return redirect()->back();
   // }



   public function update(Request $request, $id)
   {
       $validatedData = $request->validate([
           'title' => 'required|string|max:255',
           'completed' => 'required|boolean', // Assurez-vous que completed est un boolean
       ]);
   
       $task = Task::findOrFail($id);
       $task->fill($validatedData);
       $task->save();
   
       return response()->json($task);
   }





public function destroy($id)
{
    $task = Task::findOrFail($id);
    $task->delete();

    return response()->json(['message' => 'Task deleted successfully']);
}
}
