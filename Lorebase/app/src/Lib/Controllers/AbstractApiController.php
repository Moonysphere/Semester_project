<?php

namespace App\Lib\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Repositories\UserRepository;

abstract class AbstractAPIController extends AbstractController
{
    protected function apiResponse($data, int $statusCode = 200, ?array $pagination = null): Response
    {
        $response = [
            'success' => true,
            'data' => $data
        ];

        if ($pagination !== null) {
            $response['pagination'] = $pagination;
        }

        return new Response(
            json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            $statusCode,
            $this->getApiHeaders()
        );
    }

    protected function apiError(string $message, int $statusCode = 400): Response
    {
        $response = [
            'success' => false,
            'error' => $message,
            'code' => $statusCode
        ];

        return new Response(
            json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            $statusCode,
            $this->getApiHeaders()
        );
    }


    protected function getApiHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type',
            'Cache-Control' => 'public, max-age=3600'
        ];
    }


    protected function getPaginationParams(): array
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
        $offset = ($page - 1) * $limit;

        return [
            'page' => $page,
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    protected function buildPagination(int $page, int $limit, int $total): array
    {
        return [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => (int)ceil($total / $limit)
        ];
    }


    protected function hasUserFilter(): bool
    {
        return isset($_GET['user']) && $_GET['user'] !== '';
    }

    protected function getUserFilter(): ?string
    {
        $userParam = $_GET['user'] ?? null;
        $userIdParam = $_GET['user_id'] ?? null;

        if ($userParam) {
            return $this->getUserEmailByUsername($userParam);
        }
        return $userIdParam;
    }

    protected function getUserEmailByUsername(string $username): ?string
    {
        try {
            $userRepository = new UserRepository();
            $user = $userRepository->findByUsername($username);
            return $user ? $user->email : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function applyUserFilter($queryBuilder, ?string $userEmail = null)
    {
        if ($userEmail) {
            $queryBuilder
                ->andWhere('user_id', '=')
                ->addParam('user_id', $userEmail);
        } else {
            $queryBuilder
                ->andWhere('user_id', 'IS NULL');
        }

        return $queryBuilder;
    }

    protected function applySearchFilter($queryBuilder, ?string $searchTerm, string $searchField = 'name'): void
    {
        if ($searchTerm && !empty(trim($searchTerm))) {
            $queryBuilder
                ->andWhere($searchField, 'ILIKE')
                ->addParam($searchField, '%' . $searchTerm . '%');
        }
    }

    protected function getSearchParam(?string $paramName = 'search'): ?string
    {
        $search = $_GET[$paramName] ?? null;
        return $search ? trim($search) : null;
    }
}
