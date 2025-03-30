<?php

    namespace App\Http\Controllers;
    use App\Http\Requests\SortTasksRequest;
    use Illuminate\Http\Request;
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

        public function index(SortTasksRequest $request): JsonResponse
        {
            $sortBy = $request->input('sort_by', 'first_name');
            $sortOrder = $request->input('sort_order', 'asc');

            return response()->json($this->userService->getAllUsers($sortBy, $sortOrder));
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
