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
            'exportUrl' => route('tasks.export'),
            'importUrl' => route('tasks.import'),
        ]);
    }

    public function store(Request $request)
    {
        // Valider la demande
        $validatedData = $request->validate(['title' => 'required|string|max:255']);
        
        // Créer la tâche
        $task = Task::create($validatedData);
    
        // Créer une notification dans la base de données
        $task->notify(new TaskNotification($task, 'ajoutée'));
    
        // Stocker un message flash dans la session pour la redirection
        // session()->flash('success', 'Tâche ajoutée avec succès!');
    
        // Rediriger vers la page précédente ou vers une autre route
        // return redirect()->route('tasks.index')->with('success', 'Tâche ajoutée avec succès!');
       return to_route('tasks.index');
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
    
        // Rediriger vers la page des tâches
        // return redirect()->route('tasks.index')->with('success', 'Tâche mise à jour avec succès!');
        return to_route('tasks.index');
    }

    public function destroy($id)
{
    $task = Task::findOrFail($id);
    $task->delete();

    // Créer une notification
    $task->notify(new TaskNotification($task, 'supprimée'));

    // Rediriger vers la page des tâches
    // return redirect()->route('tasks.index')->with('success', 'Tâche supprimée avec succès!');
    return to_route('tasks.index');
}
}
