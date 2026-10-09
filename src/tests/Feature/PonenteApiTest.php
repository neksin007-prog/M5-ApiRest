<?php

namespace Tests\Feature;

use App\Models\Ponente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PonenteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_ponentes(): void
    {
        Ponente::create([
            'nombre' => 'Ana Ponente',
            'biografia' => 'Especialista en tecnología.',
            'especialidad' => 'Tecnología',
        ]);

        $this->getJson('/api/ponentes')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.nombre', 'Ana Ponente');
    }

    public function test_it_creates_a_ponente(): void
    {
        $datos = [
            'nombre' => 'Ana Ponente',
            'biografia' => 'Especialista en tecnología.',
            'especialidad' => 'Tecnología',
        ];

        $this->postJson('/api/ponentes', $datos)
            ->assertCreated()
            ->assertJsonPath('nombre', 'Ana Ponente')
            ->assertJsonPath('biografia', 'Especialista en tecnología.')
            ->assertJsonPath('especialidad', 'Tecnología')
            ->assertJsonStructure(['id']);

        $this->assertDatabaseHas('ponentes', $datos);
    }

    public function test_it_shows_updates_and_deletes_a_ponente(): void
    {
        $ponente = Ponente::create([
            'nombre' => 'Ana Ponente',
            'biografia' => 'Especialista en tecnología.',
            'especialidad' => 'Tecnología',
        ]);

        $this->getJson("/api/ponentes/{$ponente->id}")
            ->assertOk()
            ->assertJsonPath('id', $ponente->id);

        $datosActualizados = [
            'nombre' => 'Ana Actualizada',
            'biografia' => 'Nueva biografía.',
            'especialidad' => 'Innovación',
        ];

        $this->putJson("/api/ponentes/{$ponente->id}", $datosActualizados)
            ->assertOk()
            ->assertJsonPath('nombre', 'Ana Actualizada');

        $this->assertDatabaseHas('ponentes', $datosActualizados);

        $this->deleteJson("/api/ponentes/{$ponente->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('ponentes', ['id' => $ponente->id]);
    }

    public function test_it_requires_all_ponente_fields(): void
    {
        $this->postJson('/api/ponentes', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nombre', 'biografia', 'especialidad']);
    }
}
