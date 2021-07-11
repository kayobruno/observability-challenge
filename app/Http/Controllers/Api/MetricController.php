<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Metric\CreateRequest;
use App\Http\Requests\Metric\UpdateRequest;
use App\Models\Alert;
use App\Models\Metric;
use App\Services\AlertDatabaseService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MetricController extends Controller
{
    /**
     * @var Metric
     */
    protected $metricRepository;

    /**
     * @var AlertDatabaseService
     */
    protected $alertDatabaseService;

    /**
     * MetricController constructor.
     * @param Metric $metricRepository
     * @param AlertDatabaseService $alertDatabaseService
     */
    public function __construct(Metric $metricRepository, AlertDatabaseService $alertDatabaseService)
    {
        $this->metricRepository = $metricRepository;
        $this->alertDatabaseService = $alertDatabaseService;
    }

    /**
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        $content = $this->exportAlertsByMetricName();
        $content .= $this->exportAlertsByStatus();
        $content .= $this->exportIncidentsByAppName();

        return response($content)->header('Content-Type', 'text/plain; version=0.0.4');
    }

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function store(CreateRequest $request): JsonResponse
    {
        $fields = [];
        foreach ($request->all() as $key => $value) {
            $fields[convertCamelCaseToSnakeCase($key)] = $value;
        }
        $metric = $this->metricRepository->create($fields);

        return $this->createApiResponse($metric, Response::HTTP_CREATED);
    }

    /**
     * @param Metric $metric
     * @return JsonResponse
     */
    public function show(Metric $metric): JsonResponse
    {
        return $this->createApiResponse($metric);
    }

    /**
     * @param Metric $metric
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(Metric $metric, UpdateRequest $request): JsonResponse
    {
        try {
            $metric->update($request->all());
        } catch (\Exception $e) {
            return $this->createApiResponseErrors(
                __('messages.error.unavailable'),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->createApiResponse($metric);
    }

    /**
     * @param Metric $metric
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Metric $metric): JsonResponse
    {
        try {
            $metric->delete();
        } catch (\Exception $e) {
            return $this->createApiResponseErrors(
                __('messages.error.unavailable'),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->okButNoContent();
    }

    /**
     * @return string
     */
    private function exportIncidentsByAppName(): string
    {
        $content = PHP_EOL;
        $content .= '# app-name-incidents-qtd' . PHP_EOL;
        $incidents = $this->alertDatabaseService->getAlertsGroupByAppName();
        foreach ($incidents as $incident) {
            $content .= "app-name-incidents-qtd{app_name=\"{$incident['app_name']}\"} {$incident['total']}" . PHP_EOL;
        }

        return $content;
    }

    /**
     * @return string
     */
    private function exportAlertsByStatus(): string
    {
        $totalEnabledAlerts = Alert::where('enabled', true)->count();
        $totalDisabledAlerts = Alert::where('enabled', false)->count();

        $content = '# alerts-enabled' . PHP_EOL;
        $content .= "alerts{enabled=true} {$totalEnabledAlerts}" . PHP_EOL;
        $content .= "alerts{enabled=false} {$totalDisabledAlerts}" . PHP_EOL;

        return $content;
    }

    /**
     * @return string
     */
    private function exportAlertsByMetricName(): string
    {
        $content = '';
        $metrics = Metric::groupBy('metric_name')->select('metric_name')->get()->toArray();
        foreach ($metrics as $metric) {
            $content .= "# {$metric['metric_name']}" . PHP_EOL;
            $alerts = $this->alertDatabaseService->getAlertsGroupByMetrics($metric['metric_name']);
            foreach ($alerts as $alert) {
                $content .= "{$metric['metric_name']}{alert_id={$alert['alert_id']}} {$alert['total']}" . PHP_EOL;
            }
            $content .= PHP_EOL;
        }

        return $content;
    }
}
