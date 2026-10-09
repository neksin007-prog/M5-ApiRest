<?php

namespace App\Http\Controllers;

use App\Models\Ponente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PonenteController extends Controller
{
    private function reglas(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'biografia' => 'required|string',
            'especialidad' => 'required|string|max:255',
        ];
    }

    public function index(): JsonResponse
    {
        return response()->json(Ponente::orderBy('nombre')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate($this->reglas());

        return response()->json(Ponente::create($datos), 201);
    }

    public function show(Ponente $ponente): JsonResponse
    {
        return response()->json($ponente);
    }

    public function update(Request $request, Ponente $ponente): JsonResponse
    {
        $datos = $request->validate($this->reglas());
        $ponente->update($datos);

        return response()->json($ponente);
    }

    public function destroy(Ponente $ponente): Response
    {
        $ponente->delete();

        return response()->noContent();
    }
}
