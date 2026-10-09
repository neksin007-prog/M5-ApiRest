<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventoController extends Controller
{
    private function reglas(): array
    {
        return [
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'ubicacion'    => 'required|string|max:255',
        ];
    }

    public function index(): JsonResponse
    {
        return response()->json(Evento::orderBy('fecha_inicio')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate($this->reglas());

        return response()->json(Evento::create($datos), 201);
    }

    public function show(Evento $evento): JsonResponse
    {
        return response()->json($evento);
    }

    public function update(Request $request, Evento $evento): JsonResponse
    {
        $datos = $request->validate($this->reglas());
        $evento->update($datos);

        return response()->json($evento);
    }

    public function destroy(Evento $evento): Response
    {
        $evento->delete();

        return response()->noContent();
    }
}