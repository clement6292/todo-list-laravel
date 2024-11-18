<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Notification\DatabaseNotification;
use Inertia\Inertia;
use App\Notifications\TaskNotification;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        // Récupérer les notifications les plus récentes
        $notifications = \DB::table('notifications')
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('TodoList.vue', [
            'tasks' => $tasks,
            'notifications' => $notifications,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(['title' => 'required|string|max:255']);
        $task = Task::create($validatedData);

        // Créer une notification dans la base de données
        $task->notify(new TaskNotification($task, 'ajoutée'));

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'completed' => 'required|boolean',
        ]);

        $task = Task::findOrFail($id);
        $task->update($validatedData);

        // Créer une notification
        $task->notify(new TaskNotification($task, 'modifiée'));

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        // Créer une notification
        $task->notify(new TaskNotification($task, 'supprimée'));

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
