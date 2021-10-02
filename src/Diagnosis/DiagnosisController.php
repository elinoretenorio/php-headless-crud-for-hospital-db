<?php

declare(strict_types=1);

namespace Hospital\Diagnosis;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class DiagnosisController 
{
    const ERROR_INVALID_INPUT = "Invalid input";

    private IDiagnosisService $service;

    public function __construct(IDiagnosisService $service)
    {
        $this->service = $service;        
    }

    public function insert(RequestInterface $request, array $args): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        if (empty($data)) {
            $data = $request->getParsedBody();
        }

        /** @var DiagnosisModel $model */
        $model = $this->service->createModel($data);

        $result = $this->service->insert($model);

        return new JsonResponse(["result" => $result]);
    }

    public function update(RequestInterface $request, array $args): ResponseInterface
    {
        $diagnosisId = (int) ($args["diagnosis_id"] ?? 0);
        if ($diagnosisId <= 0) {
            return new JsonResponse(["result" => $diagnosisId, "message" => self::ERROR_INVALID_INPUT]);
        }

        $data = json_decode($request->getBody()->getContents(), true);
        if (empty($data)) {
            $data = $request->getParsedBody();
        }

        /** @var DiagnosisModel $model */
        $model = $this->service->createModel($data);
        $model->setDiagnosisId($diagnosisId);

        $result = $this->service->update($model);

        return new JsonResponse(["result" => $result]);
    }

    public function get(RequestInterface $request, array $args): ResponseInterface
    {
        $diagnosisId = (int) ($args["diagnosis_id"] ?? 0);
        if ($diagnosisId <= 0) {
            return new JsonResponse(["result" => $diagnosisId, "message" => self::ERROR_INVALID_INPUT]);
        }

        /** @var DiagnosisModel $model */
        $model = $this->service->get($diagnosisId);

        return new JsonResponse(["result" => $model->jsonSerialize()]);
    }

    public function getAll(RequestInterface $request, array $args): ResponseInterface
    {
        $models = $this->service->getAll();

        $result = [];

        /** @var DiagnosisModel $model */
        foreach ($models as $model) {
            $result[] = $model->jsonSerialize();
        }

        return new JsonResponse(["result" => $result]);
    }

    public function delete(RequestInterface $request, array $args): ResponseInterface
    {
        $diagnosisId = (int) ($args["diagnosis_id"] ?? 0);
        if ($diagnosisId <= 0) {
            return new JsonResponse(["result" => $diagnosisId, "message" => self::ERROR_INVALID_INPUT]);
        }

        $result = $this->service->delete($diagnosisId);

        return new JsonResponse(["result" => $result]);
    }
}