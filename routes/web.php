<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

use App\Exports\TasksExport;
use App\Imports\TasksImport;
use Maatwebsite\Excel\Facades\Excel;


Route::get('/', function () {
   return Inertia::render('Todolist');
});

// Route pour afficher la liste des tâches
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// Route pour stocker une nouvelle tâche
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Route pour mettre à jour une tâche existante
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');

// Route pour supprimer une tâche
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');


// Route pour exporter les tâches
Route::get('/tasks/export', function () {
   return Excel::download(new TasksExport, 'tasks.xlsx');
})->name('tasks.export');



Route::post('/tasks/import', function () {
   Excel::import(new TasksImport, request()->file('file'));

   return redirect()->route('tasks.index')->with('success', 'Tâches importées avec succès!');
})->name('tasks.import');