<?php

namespace Tests\Unit;

use App\Services\OpportunityStageService;
use PHPUnit\Framework\TestCase;

class OpportunityStageServiceTest extends TestCase
{
    public function test_permite_cambio_de_prospecto_a_negociacion(): void
    {
        $service = new OpportunityStageService();

        $resultado = $service->validarCambioEtapa('Prospecto', 'Negociación');

        $this->assertTrue($resultado['valido']);
        $this->assertEquals('Cambio permitido', $resultado['mensaje']);
    }

    public function test_permite_cambio_de_negociacion_a_cerrado_ganado(): void
    {
        $service = new OpportunityStageService();

        $resultado = $service->validarCambioEtapa('Negociación', 'Cerrado Ganado');

        $this->assertTrue($resultado['valido']);
        $this->assertEquals('Cambio permitido', $resultado['mensaje']);
    }

    public function test_permite_cambio_de_negociacion_a_cerrado_perdido(): void
    {
        $service = new OpportunityStageService();

        $resultado = $service->validarCambioEtapa('Negociación', 'Cerrado Perdido');

        $this->assertTrue($resultado['valido']);
        $this->assertEquals('Cambio permitido', $resultado['mensaje']);
    }

    public function test_no_permite_cambiar_una_oportunidad_ya_cerrada(): void
    {
        $service = new OpportunityStageService();

        $resultado = $service->validarCambioEtapa('Cerrado Ganado', 'Prospecto');

        $this->assertFalse($resultado['valido']);
        $this->assertEquals(
            'Una oportunidad cerrada ya no puede cambiar de etapa',
            $resultado['mensaje']
        );
    }

    public function test_no_permite_cambiar_a_una_etapa_inexistente(): void
    {
        $service = new OpportunityStageService();

        $resultado = $service->validarCambioEtapa('Prospecto', 'EtapaInventada');

        $this->assertFalse($resultado['valido']);
        $this->assertEquals('La nueva etapa no es válida', $resultado['mensaje']);
    }
}