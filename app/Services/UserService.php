<?php

    namespace App\Services;

    use Illuminate\Validation\ValidationException;
    use App\Models\User;

    class UserService
    {
        public function getAllUsers(string $sortBy = 'first_name', string $sortOrder = 'asc')
        {
            $allowedSortFields = ['first_name', 'last_name', 'email'];

            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'first_name';
            }

            return User::orderBy($sortBy, $sortOrder)->paginate(10);
        }


        public function getUserById(int $id)
        {
            return User::findOrFail($id);
        }

        public function createUser(array $data)
        {
            return User::createUser($data);
        }

        public function updateUser(int $id, array $data)
        {
            $user = User::findOrFail($id);

            $errors = [];

            if (!empty($data['login']) && User::where('login', $data['login'])->where('id', '!=', $id)->exists()) {
                $errors['login'] = ['The login is already taken by another user.'];
            }

            if (!empty($data['email']) && User::where('email', $data['email'])->where('id', '!=', $id)->exists()) {
                $errors['email'] = ['The email is already taken by another user.'];
            }

            if (!empty($errors)) {
                throw ValidationException::withMessages($errors);
            }

            return $user->updateUser($data);
        }


        public function deleteUser(int $id)
        {
            return User::destroy($id);
        }
    }
