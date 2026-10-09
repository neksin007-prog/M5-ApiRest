<?php

namespace Tests\Feature;

use App\Models\Asistente;
use App\Models\Evento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsistenteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_asistentes(): void
    {
        $evento = $this->crearEvento();
        Asistente::create([
            'nombre' => 'Luis Asistente',
            'email' => 'luis@example.com',
            'telefono' => '5551234567',
            'evento_id' => $evento->id,
        ]);

        $this->getJson('/api/asistentes')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.nombre', 'Luis Asistente')
            ->assertJsonPath('0.evento_id', $evento->id);
    }

    public function test_it_creates_an_asistente(): void
    {
        $evento = $this->crearEvento();
        $datos = [
            'nombre' => 'Luis Asistente',
            'email' => 'luis@example.com',
            'telefono' => '5551234567',
            'evento_id' => $evento->id,
        ];

        $this->postJson('/api/asistentes', $datos)
            ->assertCreated()
            ->assertJsonPath('nombre', 'Luis Asistente')
            ->assertJsonPath('email', 'luis@example.com')
            ->assertJsonPath('telefono', '5551234567')
            ->assertJsonPath('evento_id', $evento->id)
            ->assertJsonStructure(['id']);

        $this->assertDatabaseHas('asistentes', $datos);
    }

    public function test_it_shows_updates_and_deletes_an_asistente(): void
    {
        $evento = $this->crearEvento();
        $asistente = Asistente::create([
            'nombre' => 'Luis Asistente',
            'email' => 'luis@example.com',
            'telefono' => '5551234567',
            'evento_id' => $evento->id,
        ]);

        $this->getJson("/api/asistentes/{$asistente->id}")
            ->assertOk()
            ->assertJsonPath('id', $asistente->id);

        $datosActualizados = [
            'nombre' => 'Luis Actualizado',
            'email' => 'luis.updated@example.com',
            'telefono' => '5557654321',
            'evento_id' => $evento->id,
        ];

        $this->putJson("/api/asistentes/{$asistente->id}", $datosActualizados)
            ->assertOk()
            ->assertJsonPath('nombre', 'Luis Actualizado');

        $this->assertDatabaseHas('asistentes', $datosActualizados);

        $this->deleteJson("/api/asistentes/{$asistente->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('asistentes', ['id' => $asistente->id]);
    }

    public function test_it_validates_required_fields_and_event_id(): void
    {
        $this->postJson('/api/asistentes', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nombre', 'email', 'telefono', 'evento_id']);

        $this->postJson('/api/asistentes', [
            'nombre' => 'Luis Asistente',
            'email' => 'correo-invalido',
            'telefono' => '5551234567',
            'evento_id' => 999,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'evento_id']);
    }

    private function crearEvento(): Evento
    {
        return Evento::create([
            'titulo' => 'Congreso',
            'descripcion' => 'Evento de prueba.',
            'fecha_inicio' => '2026-10-10 09:00:00',
            'fecha_fin' => '2026-10-10 17:00:00',
            'ubicacion' => 'Auditorio',
        ]);
    }
}
