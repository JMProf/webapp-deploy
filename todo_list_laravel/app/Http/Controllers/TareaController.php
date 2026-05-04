<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea; // Importamos el modelo para usar la base de datos

class TareaController extends Controller
{
    // Listar tareas
    public function index() {
        $tareas = Tarea::all(); // Equivale a SELECT * FROM tareas
        return view('inicio', compact('tareas')); // Nos enviará a una vista llamada "inicio"
    }

    // Guardar nueva tarea
    public function store(Request $request) {
        $request->validate(['nueva_tarea' => 'required']); // Validación rápida

        $tarea = new Tarea();
        $tarea->nombre = $request->nueva_tarea;
        $tarea->save(); // Equivale a INSERT INTO...

        return redirect()->route('tareas.index'); // El patrón PRG que usaste, pero simplificado
    }

    // Borrar tarea
    public function destroy($id) {
        $tarea = Tarea::findOrFail($id);
        $tarea->delete(); // Equivale a DELETE FROM...

        return redirect()->route('tareas.index');
    }
}