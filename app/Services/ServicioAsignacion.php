<?php

namespace App\Services;

use App\Exceptions\SinDisponibilidadException;
use App\Support\CatalogoHospedaje;

class ServicioAsignacion
{
    private const PESO_NINO = 0.5;

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
        $personas = (int) ceil($adultos + ($ninos * self::PESO_NINO));

        $libres = $this->habitacionesDisponibles($ubicacion, $entrada, $salida, $personas);

        if ($libres === []) {
            throw new SinDisponibilidadException(
                $this->mensajeSinDisponibilidad($ubicacion, $entrada, $salida, $personas)
            );
        }

        if ($tipo !== null) {
            foreach ($libres as $habitacion) {
                if ($habitacion['tipo'] === $tipo) {
                    return ['habitacion' => $habitacion, 'aviso' => null];
                }
            }
        }

        $elegida = $this->masBarata($libres);

        $aviso = $tipo === null
            ? null
            : "No hay habitaciones {$tipo} disponibles; se asignó una {$elegida['tipo']}.";

        return ['habitacion' => $elegida, 'aviso' => $aviso];
    }

    /**
     * Habitaciones de la ubicación con capacidad suficiente y libres todas las noches.
     *
     * @return array<int, array{id: string, ubicacion: string, tipo: string}>
     */
    private function habitacionesDisponibles(string $ubicacion, string $entrada, string $salida, int $personas): array
    {
        $disponibles = [];

        foreach (CatalogoHospedaje::habitaciones() as $habitacion) {
            if ($habitacion['ubicacion'] !== $ubicacion) {
                continue;
            }

            if (CatalogoHospedaje::TIPOS[$habitacion['tipo']]['capacidad'] < $personas) {
                continue;
            }

            if ($this->estaOcupada($habitacion['id'], $entrada, $salida)) {
                continue;
            }

            $disponibles[] = $habitacion;
        }

        return $disponibles;
    }

    /**
     * Dos estancias se traslapan si cada una empieza antes de que la otra termine.
     * Salir el mismo día que otro entra NO es traslape.
     */
    private function estaOcupada(string $idHabitacion, string $entrada, string $salida): bool
    {
        foreach (CatalogoHospedaje::reservas() as $reserva) {
            if ($reserva['habitacion'] === $idHabitacion
                && $reserva['entrada'] < $salida
                && $reserva['salida'] > $entrada) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, array{id: string, ubicacion: string, tipo: string}>  $habitaciones
     * @return array{id: string, ubicacion: string, tipo: string}
     */
    private function masBarata(array $habitaciones): array
    {
        $elegida = $habitaciones[0];

        foreach ($habitaciones as $habitacion) {
            if (CatalogoHospedaje::TIPOS[$habitacion['tipo']]['tarifa'] < CatalogoHospedaje::TIPOS[$elegida['tipo']]['tarifa']) {
                $elegida = $habitacion;
            }
        }

        return $elegida;
    }

    private function mensajeSinDisponibilidad(string $ubicacion, string $entrada, string $salida, int $personas): string
    {
        $mensaje = "No hay habitaciones disponibles en {$ubicacion} para esas fechas y {$personas} persona(s).";

        foreach (array_keys(CatalogoHospedaje::UBICACIONES) as $otra) {
            if ($otra !== $ubicacion && $this->habitacionesDisponibles($otra, $entrada, $salida, $personas) !== []) {
                return $mensaje." Sugerencia: pruebe en {$otra}, que tiene disponibilidad.";
            }
        }

        return $mensaje;
    }
}
