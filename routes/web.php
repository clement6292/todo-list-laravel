<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
   return Inertia::render('Todolist');
});

// Route pour afficher la liste des tâches
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// Route pour stocker une nouvelle tâche
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Route pour mettre à jour une tâche existante
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

// Route pour supprimer une tâche
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');