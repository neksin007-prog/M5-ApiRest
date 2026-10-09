<?php

namespace App\Http\Controllers;

use App\Models\Asistente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class AsistenteController extends Controller
{
    private function reglas(?Asistente $asistente = null): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('asistentes', 'email')->ignore($asistente),
            ],
            'telefono' => 'required|string|max:30',
            'evento_id' => 'required|integer|exists:eventos,id',
        ];
    }

    public function index(): JsonResponse
    {
        return response()->json(Asistente::orderBy('nombre')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate($this->reglas());

        return response()->json(Asistente::create($datos), 201);
    }

    public function show(Asistente $asistente): JsonResponse
    {
        return response()->json($asistente);
    }

    public function update(Request $request, Asistente $asistente): JsonResponse
    {
        $datos = $request->validate($this->reglas($asistente));
        $asistente->update($datos);

        return response()->json($asistente);
    }

    public function destroy(Asistente $asistente): Response
    {
        $asistente->delete();

        return response()->noContent();
    }
}
