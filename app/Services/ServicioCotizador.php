<?php

namespace App\Services;

class ServicioCotizador
{
    public function __construct(
        private ServicioAsignacion $asignacion,
        private ServicioPrecio $precio
    ) {}

    /**
     * @param  array<string, mixed>  $datos
     * @return array<string, mixed>
     */
    public function cotizar(array $datos): array
    {
        $entrada = (string) $datos['entrada'];
        $salida = (string) $datos['salida'];

        $asignada = $this->asignacion->asignar(
            (string) $datos['ubicacion'],
            $entrada,
            $salida,
            (int) $datos['adultos'],
            (int) ($datos['ninos'] ?? 0),
            isset($datos['tipo']) ? (string) $datos['tipo'] : null
        );

        $desglose = $this->precio->calcular($asignada['habitacion'], $entrada, $salida);

        return [
            'habitacion' => $asignada['habitacion'],
            'aviso' => $asignada['aviso'],
            'desglose' => $desglose,
        ];
    }
}
