<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Alert\CreateRequest;
use App\Http\Requests\Alert\UpdateRequest;
use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AlertController extends Controller
{
    /**
     * @var Alert
     */
    protected $alertRepository;

    /**
     * AlertController constructor.
     * @param Alert $alertRepository
     */
    public function __construct(Alert $alertRepository)
    {
        $this->alertRepository = $alertRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $alerts = $this->alertRepository->latest()->get();
        return $this->createApiResponse(['items' => $alerts]);
    }

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function store(CreateRequest $request): JsonResponse
    {
        try {
            $alert = $this->alertRepository->create($request->all());

            return $this->createApiResponse($alert, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->createApiResponseErrors(
                __('messages.error.unavailable'),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }
    }

    /**
     * @param Alert $alert
     * @return JsonResponse
     */
    public function show(Alert $alert): JsonResponse
    {
        return $this->createApiResponse($alert);
    }

    /**
     * @param Alert $alert
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(Alert $alert, UpdateRequest $request): JsonResponse
    {
        try {
            $alert->update($request->all());
        } catch (\Exception $e) {
            return $this->createApiResponseErrors(
                __('messages.error.unavailable'),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->createApiResponse($alert);
    }

    /**
     * @param Alert $alert
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Alert $alert): JsonResponse
    {
        try {
            // TODO: Bloquear remoção caso exista relacionamento com qualquer entidade
            $alert->delete();
        } catch (\Exception $e) {
            return $this->createApiResponseErrors(
                __('messages.error.unavailable'),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->okButNoContent();
    }
}
