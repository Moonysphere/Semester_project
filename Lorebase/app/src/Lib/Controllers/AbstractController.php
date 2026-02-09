<?php

namespace App\Lib\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Repositories\UserRepository;

abstract class AbstractController
{
    public abstract function process(Request $request): Response;

    protected function render(string $file, string $template, array $data = []): Response
    {
        $response = new Response();
        extract($data);
        ob_start();
        require_once __DIR__ . "/../../../views/{$file}/{$template}.html";
        $response->setContent(ob_get_clean());
        $response->addHeader('Content-Type', 'text/html');

        return $response;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function getCurrentRole(): string
    {
        return $_SESSION['user']['role'] ?? 'reader';
    }

    protected function isAdmin(): bool
    {
        return $this->getCurrentRole() === 'admin';
    }

    protected function isEditor(): bool
    {
        $role = $this->getCurrentRole();
        return $role === 'editor' || $role === 'admin';
    }
    protected function isAuthor(): bool
    {
        $role = $this->getCurrentRole();
        return in_array($role, ['author', 'editor', 'admin']);
    }

    protected function requireRole(string $minRole): ?Response
    {
        $roles = ['reader' => 0, 'author' => 1, 'editor' => 2, 'admin' => 3];

        $userRoleLevel = $roles[$this->getCurrentRole()] ?? 0;
        $requiredLevel = $roles[$minRole] ?? 0;

        if ($userRoleLevel < $requiredLevel) {
            return new Response(
                json_encode(['success' => false, 'error' => 'access_denied']),
                403,
                ['Content-Type' => 'application/json']
            );
        }

        return null;
    }

    protected function requireRoleOrRedirect(string $minRole, string $redirectUrl = '/login'): ?Response
    {
        $roles = ['reader' => 0, 'author' => 1, 'editor' => 2, 'admin' => 3];

        $userRoleLevel = $roles[$this->getCurrentRole()] ?? 0;
        $requiredLevel = $roles[$minRole] ?? 0;

        if ($userRoleLevel < $requiredLevel) {
            return new Response('', 302, ['Location' => $redirectUrl]);
        }

        return null;
    }

    protected function getUserFromUsername(Request $request): ?array
    {
        $username = $request->getSlug('username');

        if (!$username) {
            return null;
        }

        $userRepository = new UserRepository();
        $user = $userRepository->findByUsername($username);

        if (!$user) {
            return null;
        }

        $currentUserEmail = $_SESSION['user']['email'] ?? null;
        $isOwner = $currentUserEmail === $user->email;

        return [
            'user' => $user,
            'isOwner' => $isOwner,
            'currentUserEmail' => $currentUserEmail,
        ];
    }


    protected function isEntityOwner(?object $entity): bool
    {
        if (!$entity || !isset($entity->user_id)) {
            return false;
        }

        $currentUserEmail = $_SESSION['user']['email'] ?? null;
        return $currentUserEmail !== null && $entity->user_id === $currentUserEmail;
    }
}
