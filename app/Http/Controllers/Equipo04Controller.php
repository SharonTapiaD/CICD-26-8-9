<?php

namespace App\Http\Controllers;

use App\Http\Requests\CotizarRequest;
use App\Http\Responses\RespuestaApi;
use App\Services\ServicioCotizador;
use Illuminate\Http\JsonResponse;

class Equipo04Controller extends Controller
{
    public function __construct(private ServicioCotizador $cotizador) {}

    public function cotizar(CotizarRequest $request): JsonResponse
    {
        $resultado = $this->cotizador->cotizar($request->validated());

        return RespuestaApi::exito($resultado);
    }
}
