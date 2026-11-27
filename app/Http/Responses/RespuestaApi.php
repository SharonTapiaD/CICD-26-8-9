<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class RespuestaApi
{
    public static function exito(mixed $datos): JsonResponse
    {
        // TODO (D): implementar la respuesta de éxito.
        throw new \LogicException('Pendiente: pieza de D');
    }

    public static function error(string $mensaje, int $codigo): JsonResponse
    {
        // TODO (D): implementar la respuesta de error.
        throw new \LogicException('Pendiente: pieza de D');
    }
}
