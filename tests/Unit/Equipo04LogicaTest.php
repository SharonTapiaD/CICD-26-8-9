<?php

namespace Tests\Unit;

use App\Exceptions\SinDisponibilidadException;
use App\Services\ServicioAsignacion;
use PHPUnit\Framework\TestCase;

class Equipo04LogicaTest extends TestCase
{
    public function test_limite_salir_el_dia_que_otro_entra_es_valido(): void
    {
        // P-105 (playa familiar) está reservada 2026-12-10 a 2026-12-15.
        $r = (new ServicioAsignacion)->asignar('playa', '2026-12-15', '2026-12-17', 5, 0, 'familiar');

        $this->assertSame('P-105', $r['habitacion']['id']);
        $this->assertNull($r['aviso']);
    }

    public function test_limite_entrar_el_dia_que_otra_reserva_sale_es_valido(): void
    {
        $r = (new ServicioAsignacion)->asignar('playa', '2026-12-08', '2026-12-10', 5, 0, 'familiar');

        $this->assertSame('P-105', $r['habitacion']['id']);
    }

    public function test_limite_traslape_de_una_noche_ocupa_la_habitacion(): void
    {
        // 5 personas en playa solo caben en la familiar, que está ocupada esas noches.
        $this->expectException(SinDisponibilidadException::class);

        (new ServicioAsignacion)->asignar('playa', '2026-12-09', '2026-12-11', 5, 0, null);
    }

    public function test_limite_dos_adultos_y_un_nino_son_tres_personas_y_no_caben_en_sencilla(): void
    {
        // 2 + 0.5 = 2.5 -> redondea a 3 -> sencilla (cap. 2) no sirve.
        $r = (new ServicioAsignacion)->asignar('centro', '2026-11-01', '2026-11-03', 2, 1, 'sencilla');

        $this->assertSame('doble', $r['habitacion']['tipo']);
        $this->assertNotNull($r['aviso']);
    }

    public function test_limite_un_adulto_y_un_nino_son_dos_personas_caben_en_sencilla(): void
    {
        // 1 + 0.5 = 1.5 -> redondea a 2 -> cabe exacto en sencilla.
        $r = (new ServicioAsignacion)->asignar('centro', '2026-11-01', '2026-11-03', 1, 1, 'sencilla');

        $this->assertSame('sencilla', $r['habitacion']['tipo']);
        $this->assertNull($r['aviso']);
    }

    public function test_limite_seis_personas_caben_exacto_en_familiar(): void
    {
        $r = (new ServicioAsignacion)->asignar('centro', '2026-11-01', '2026-11-03', 6, 0, null);

        $this->assertSame('C-205', $r['habitacion']['id']);
    }

    public function test_limite_siete_personas_no_caben_en_ninguna_habitacion(): void
    {
        $this->expectException(SinDisponibilidadException::class);

        (new ServicioAsignacion)->asignar('centro', '2026-11-01', '2026-11-03', 7, 0, null);
    }

    public function test_sin_tipo_pedido_asigna_la_mas_barata_sin_aviso(): void
    {
        $r = (new ServicioAsignacion)->asignar('centro', '2026-11-01', '2026-11-03', 2, 0, null);

        $this->assertSame('sencilla', $r['habitacion']['tipo']);
        $this->assertNull($r['aviso']);
    }

    public function test_cambio_de_tipo_cuando_la_pedida_esta_ocupada_avisa(): void
    {
        // M-301 (única sencilla de montana) ocupada 2026-12-05 a 2026-12-08.
        $r = (new ServicioAsignacion)->asignar('montana', '2026-12-06', '2026-12-07', 2, 0, 'sencilla');

        $this->assertSame('doble', $r['habitacion']['tipo']);
        $this->assertStringContainsString('sencilla', (string) $r['aviso']);
    }

    public function test_sin_disponibilidad_sugiere_otra_ubicacion(): void
    {
        try {
            (new ServicioAsignacion)->asignar('playa', '2026-12-11', '2026-12-12', 5, 0, null);
            $this->fail('Debió lanzar SinDisponibilidadException');
        } catch (SinDisponibilidadException $e) {
            $this->assertStringContainsString('Sugerencia', $e->getMessage());
            $this->assertStringContainsString('centro', $e->getMessage());
        }
    }
}
