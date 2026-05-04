<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController; // Le decimos dónde está nuestro controlador

// 1. Mostrar la lista (Página principal)
Route::get('/', [TareaController::class, 'index'])->name('tareas.index');

// 2. Recibir el formulario y guardar la tarea
Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');

// 3. Borrar la tarea (Recibiendo el ID)
Route::delete('/tareas/{id}', [TareaController::class, 'destroy'])->name('tareas.destroy');