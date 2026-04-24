<?php

namespace App\Services;

class OpportunityStageService
{
    private array $etapasValidas = [
        'Prospecto',
        'Negociación',
        'Cerrado Ganado',
        'Cerrado Perdido',
    ];

    public function validarCambioEtapa(string $etapaActual, string $nuevaEtapa): array
    {
        if (!in_array($etapaActual, $this->etapasValidas)) {
            return [
                'valido' => false,
                'mensaje' => 'La etapa actual no es válida',
            ];
        }

        if (!in_array($nuevaEtapa, $this->etapasValidas)) {
            return [
                'valido' => false,
                'mensaje' => 'La nueva etapa no es válida',
            ];
        }

        if ($etapaActual === $nuevaEtapa) {
            return [
                'valido' => false,
                'mensaje' => 'No se puede cambiar a la misma etapa',
            ];
        }

        if ($etapaActual === 'Cerrado Ganado' || $etapaActual === 'Cerrado Perdido') {
            return [
                'valido' => false,
                'mensaje' => 'Una oportunidad cerrada ya no puede cambiar de etapa',
            ];
        }

        if ($etapaActual === 'Prospecto' && $nuevaEtapa === 'Negociación') {
            return [
                'valido' => true,
                'mensaje' => 'Cambio permitido',
            ];
        }

        if ($etapaActual === 'Negociación' &&
            ($nuevaEtapa === 'Cerrado Ganado' || $nuevaEtapa === 'Cerrado Perdido')) {
            return [
                'valido' => true,
                'mensaje' => 'Cambio permitido',
            ];
        }

        return [
            'valido' => false,
            'mensaje' => 'Transición de etapa no permitida',
        ];
    }
}