<?php

namespace App\Services;

class ServicioAsignacion
{
    /**
     * @return array{habitacion: array{id: string, ubicacion: string, tipo: string}, aviso: string|null}
     */
    public function asignar(
        string $ubicacion,
        string $entrada,
        string $salida,
        int $adultos,
        int $ninos,
        ?string $tipo
    ): array {
        // TODO (B): reemplazar este cascarón con la lógica real.
        throw new \LogicException('Pendiente: pieza de B');
    }
}
