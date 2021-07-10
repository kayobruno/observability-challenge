<?php

namespace App\Observers;

use App\Models\Alert;
use App\Models\Incident;
use App\Models\Metric;
use App\Services\AlertDatabaseService;
use Illuminate\Support\Facades\Log;

class MetricObserver
{
    /**
     * @var Incident
     */
    protected $incidentRepository;

    /**
     * @var AlertDatabaseService
     */
    protected $alertDatabaseService;

    /**
     * MetricObserver constructor.
     * @param Incident $incidentRepository
     * @param AlertDatabaseService $alertDatabaseService
     */
    public function __construct(Incident $incidentRepository, AlertDatabaseService $alertDatabaseService)
    {
        $this->incidentRepository = $incidentRepository;
        $this->alertDatabaseService = $alertDatabaseService;
    }

    /**
     * Handle the metric "created" event.
     *
     * @param  Metric  $metric
     * @return void
     */
    public function created(Metric $metric)
    {
        $alerts = [];
        $conditions = Alert::getConstantsValuesByPrefix('CONDITION_');
        foreach ($conditions as $condition) {
            $data = $this->alertDatabaseService->getAlertsByMetric(
                $metric->app_name,
                $metric->metric_name,
                $metric->value,
                $condition
            );

            $alerts = array_merge($alerts, $data);
        }

        if (!count($alerts)) {
            Log::info(json_encode([
                'METRIC' => $metric,
                'MESSAGE' => 'Nenhum alerta encontrado para a métrica cadastrada',
            ]));
        }

        $this->createIncident($alerts, $metric);
    }

    /**
     * @param array $alerts
     * @param Metric $metric
     */
    private function createIncident(array $alerts, Metric $metric)
    {
        foreach ($alerts as $alert) {
            if (!$alert['enabled']) {
                Log::info(json_encode([
                    'ALERT' => $alert,
                    'METRIC' => $metric,
                    'MESSAGE' => 'Alerta teve as condições satisfeitas mas o mesmo encontra-se desabilitado',
                ]));
                continue;
            }

            $this->incidentRepository->create([
                'alert_id' => $alert['alert_id'],
            ]);
        }
    }
}
