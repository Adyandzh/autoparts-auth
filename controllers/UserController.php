<?php

require_once __DIR__ . '/../models/User.php';

class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    private function getInput(): array
    {
        $input = file_get_contents('php://input');

        $data = json_decode($input, true);

        if (!is_array($data)) {
            return [];
        }

        return $data;
    }

    private function response(string $status, string $message, array $data = [], int $code = 200): void
    {
        http_response_code($code);

        echo json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
    }

    private function hidePassword(array $user): array
    {
        unset($user['password_hash']);

        return $user;
    }

    public function register(): void
    {
        $data = $this->getInput();

        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            $this->response('error', 'Заполните все поля', [], 400);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response('error', 'Некорректный email', [], 400);
            return;
        }

        if (strlen($password) < 6) {
            $this->response('error', 'Пароль должен быть не меньше 6 символов', [], 400);
            return;
        }

        if ($this->userModel->findByEmail($email)) {
            $this->response('error', 'Пользователь с таким email уже существует', [], 409);
            return;
        }

        $user = $this->userModel->create($name, $email, $password);

        $this->response(
            'success',
            'Пользователь зарегистрирован',
            $this->hidePassword($user),
            201
        );
    }

    public function login(): void
    {
        $data = $this->getInput();

        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->response('error', 'Введите email и пароль', [], 400);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $this->response('error', 'Пользователь не найден', [], 404);
            return;
        }

        if (!password_verify($password, $user['password_hash'])) {
            $this->response('error', 'Неверный пароль', [], 401);
            return;
        }

        $this->response(
            'success',
            'Авторизация выполнена успешно',
            $this->hidePassword($user)
        );
    }

    public function getAllUsers(): void
    {
        $users = $this->userModel->getAll();

        $safeUsers = [];

        foreach ($users as $user) {
            $safeUsers[] = $this->hidePassword($user);
        }

        $this->response('success', 'Список пользователей получен', $safeUsers);
    }

    public function getUserById($id): void
    {
        $id = (int)$id;

        $user = $this->userModel->findById($id);

        if (!$user) {
            $this->response('error', 'Пользователь не найден', [], 404);
            return;
        }

        $this->response(
            'success',
            'Пользователь найден',
            $this->hidePassword($user)
        );
    }

    public function updatePassword($id): void
    {
        $id = (int)$id;

        $data = $this->getInput();

        $newPassword = trim($data['password'] ?? '');

        if ($newPassword === '') {
            $this->response('error', 'Введите новый пароль', [], 400);
            return;
        }

        if (strlen($newPassword) < 6) {
            $this->response('error', 'Пароль должен быть не меньше 6 символов', [], 400);
            return;
        }

        $user = $this->userModel->findById($id);

        if (!$user) {
            $this->response('error', 'Пользователь не найден', [], 404);
            return;
        }

        $this->userModel->updatePassword($id, $newPassword);

        $this->response('success', 'Пароль пользователя изменён');
    }

    public function deleteUser($id): void
    {
        $id = (int)$id;

        $user = $this->userModel->findById($id);

        if (!$user) {
            $this->response('error', 'Пользователь не найден', [], 404);
            return;
        }

        $this->userModel->delete($id);

        $this->response('success', 'Пользователь удалён');
    }
}