<?php

declare(strict_types=1);

namespace Hospital\CafeteriaStaff;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class CafeteriaStaffController 
{
    const ERROR_INVALID_INPUT = "Invalid input";

    private ICafeteriaStaffService $service;

    public function __construct(ICafeteriaStaffService $service)
    {
        $this->service = $service;        
    }

    public function insert(RequestInterface $request, array $args): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        if (empty($data)) {
            $data = $request->getParsedBody();
        }

        /** @var CafeteriaStaffModel $model */
        $model = $this->service->createModel($data);

        $result = $this->service->insert($model);

        return new JsonResponse(["result" => $result]);
    }

    public function update(RequestInterface $request, array $args): ResponseInterface
    {
        $cafetriaStaffId = (int) ($args["cafetria_staff_id"] ?? 0);
        if ($cafetriaStaffId <= 0) {
            return new JsonResponse(["result" => $cafetriaStaffId, "message" => self::ERROR_INVALID_INPUT]);
        }

        $data = json_decode($request->getBody()->getContents(), true);
        if (empty($data)) {
            $data = $request->getParsedBody();
        }

        /** @var CafeteriaStaffModel $model */
        $model = $this->service->createModel($data);
        $model->setCafetriaStaffId($cafetriaStaffId);

        $result = $this->service->update($model);

        return new JsonResponse(["result" => $result]);
    }

    public function get(RequestInterface $request, array $args): ResponseInterface
    {
        $cafetriaStaffId = (int) ($args["cafetria_staff_id"] ?? 0);
        if ($cafetriaStaffId <= 0) {
            return new JsonResponse(["result" => $cafetriaStaffId, "message" => self::ERROR_INVALID_INPUT]);
        }

        /** @var CafeteriaStaffModel $model */
        $model = $this->service->get($cafetriaStaffId);

        return new JsonResponse(["result" => $model->jsonSerialize()]);
    }

    public function getAll(RequestInterface $request, array $args): ResponseInterface
    {
        $models = $this->service->getAll();

        $result = [];

        /** @var CafeteriaStaffModel $model */
        foreach ($models as $model) {
            $result[] = $model->jsonSerialize();
        }

        return new JsonResponse(["result" => $result]);
    }

    public function delete(RequestInterface $request, array $args): ResponseInterface
    {
        $cafetriaStaffId = (int) ($args["cafetria_staff_id"] ?? 0);
        if ($cafetriaStaffId <= 0) {
            return new JsonResponse(["result" => $cafetriaStaffId, "message" => self::ERROR_INVALID_INPUT]);
        }

        $result = $this->service->delete($cafetriaStaffId);

        return new JsonResponse(["result" => $result]);
    }
}