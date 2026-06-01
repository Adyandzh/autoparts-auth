<?php

class User
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../data/users.json';

        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        }
    }

    public function getAll(): array
    {
        $data = file_get_contents($this->file);

        $users = json_decode($data, true);

        if (!is_array($users)) {
            return [];
        }

        return $users;
    }

    public function saveAll(array $users): void
    {
        file_put_contents(
            $this->file,
            json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    public function findById(int $id): ?array
    {
        $users = $this->getAll();

        foreach ($users as $user) {
            if ((int)$user['id'] === $id) {
                return $user;
            }
        }

        return null;
    }

    public function findByEmail(string $email): ?array
    {
        $users = $this->getAll();

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }

        return null;
    }

    public function create(string $name, string $email, string $password): array
    {
        $users = $this->getAll();

        $newId = 1;

        if (!empty($users)) {
            $lastUser = end($users);
            $newId = $lastUser['id'] + 1;
        }

        $newUser = [
            'id' => $newId,
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $users[] = $newUser;

        $this->saveAll($users);

        return $newUser;
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $users = $this->getAll();

        foreach ($users as &$user) {
            if ((int)$user['id'] === $id) {
                $user['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->saveAll($users);
                return true;
            }
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $users = $this->getAll();

        foreach ($users as $index => $user) {
            if ((int)$user['id'] === $id) {
                array_splice($users, $index, 1);
                $this->saveAll($users);
                return true;
            }
        }

        return false;
    }
}