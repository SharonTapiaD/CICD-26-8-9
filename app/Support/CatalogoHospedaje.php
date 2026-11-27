<?php

namespace App\Support;

class CatalogoHospedaje
{
    public const TIPOS = [
        'sencilla' => ['capacidad' => 2, 'tarifa' => 800],
        'doble' => ['capacidad' => 4, 'tarifa' => 1200],
        'familiar' => ['capacidad' => 6, 'tarifa' => 1800],
    ];

    public const UBICACIONES = [
        'playa' => 1.3,
        'centro' => 1.0,
        'montana' => 1.1,
    ];

    /**
     * @return array<int, array{id: string, ubicacion: string, tipo: string}>
     */
    public static function habitaciones(): array
    {
        return [
            ['id' => 'P-101', 'ubicacion' => 'playa', 'tipo' => 'sencilla'],
            ['id' => 'P-102', 'ubicacion' => 'playa', 'tipo' => 'sencilla'],
            ['id' => 'P-103', 'ubicacion' => 'playa', 'tipo' => 'doble'],
            ['id' => 'P-104', 'ubicacion' => 'playa', 'tipo' => 'doble'],
            ['id' => 'P-105', 'ubicacion' => 'playa', 'tipo' => 'familiar'],
            ['id' => 'C-201', 'ubicacion' => 'centro', 'tipo' => 'sencilla'],
            ['id' => 'C-202', 'ubicacion' => 'centro', 'tipo' => 'sencilla'],
            ['id' => 'C-203', 'ubicacion' => 'centro', 'tipo' => 'doble'],
            ['id' => 'C-204', 'ubicacion' => 'centro', 'tipo' => 'doble'],
            ['id' => 'C-205', 'ubicacion' => 'centro', 'tipo' => 'familiar'],
            ['id' => 'M-301', 'ubicacion' => 'montana', 'tipo' => 'sencilla'],
            ['id' => 'M-302', 'ubicacion' => 'montana', 'tipo' => 'doble'],
            ['id' => 'M-303', 'ubicacion' => 'montana', 'tipo' => 'doble'],
            ['id' => 'M-304', 'ubicacion' => 'montana', 'tipo' => 'familiar'],
        ];
    }

    /**
     * @return array<int, array{habitacion: string, entrada: string, salida: string}>
     */
    public static function reservas(): array
    {
        return [
            ['habitacion' => 'P-105', 'entrada' => '2026-12-10', 'salida' => '2026-12-15'],
            ['habitacion' => 'C-203', 'entrada' => '2026-12-20', 'salida' => '2026-12-23'],
            ['habitacion' => 'M-301', 'entrada' => '2026-12-05', 'salida' => '2026-12-08'],
        ];
    }
}
