<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Metric\UpdateRequest;
use App\Http\Requests\Metric\CreateRequest;
use App\Models\Metric;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MetricController extends Controller
{
    /**
     * @var Metric
     */
    protected $metricRepository;

    /**
     * MetricController constructor.
     * @param Metric $metricRepository
     */
    public function __construct(Metric $metricRepository)
    {
        $this->metricRepository = $metricRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $incidents = $this->metricRepository->latest()->get();
        return $this->createApiResponse(['items' => $incidents]);
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
}
