<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\JsonResponse;
    use App\Services\UserService;
    use App\Http\Requests\StoreUserRequest;
    use App\Http\Requests\UpdateUserRequest;

    class UserController extends Controller
    {
        protected UserService $userService;

        public function __construct(UserService $userService)
        {
            $this->userService = $userService;
        }

        public function index(): JsonResponse
        {
            return response()->json($this->userService->getAllUsers());
        }

        public function show(int $id): JsonResponse
        {
            return response()->json($this->userService->getUserById($id));
        }

        public function store(StoreUserRequest $request): JsonResponse
        {
            return response()->json($this->userService->createUser($request->all()));
        }

        public function update(UpdateUserRequest $request, int $id): JsonResponse
        {
            return response()->json($this->userService->updateUser($id, $request->all()));
        }

        public function destroy(int $id): JsonResponse
        {
            return response()->json($this->userService->deleteUser($id));
        }
    }
