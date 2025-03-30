<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\StoreTaskRequest;
    use App\Http\Requests\UpdateTaskRequest;
    use App\Services\TaskService;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class TaskController extends Controller
    {
        protected TaskService $taskService;

        public function __construct(TaskService $taskService)
        {
            $this->taskService = $taskService;
        }

        public function index(Request $request, int $userId): JsonResponse
        {
            $tasks = $this->taskService->getAllTasks($userId, $request->all());
            return response()->json($tasks);
        }

        public function show(int $userId, int $taskId): JsonResponse
        {
            return response()->json($this->taskService->getTaskById($userId, $taskId));
        }

        public function store(StoreTaskRequest $request, int $userId): JsonResponse
        {
            return response()->json($this->taskService->createTask($userId, $request->all()), 201);
        }

        public function update(UpdateTaskRequest $request, int $userId, int $taskId): JsonResponse
        {
            return response()->json($this->taskService->updateTask($userId, $taskId, $request->all()));
        }

        public function destroy(int $userId, int $taskId): JsonResponse
        {
            return response()->json(['deleted' => $this->taskService->deleteTask($userId, $taskId)]);
        }

        public function destroyUnprocessed(int $userId): JsonResponse
        {
            return response()->json(['deleted' => $this->taskService->deleteAllNewTasks($userId)]);
        }

        public function statsByUser(int $userId): JsonResponse
        {
            return $this->taskService->getTaskStatsByUser($userId);
        }

        public function statsForAllUsers(): JsonResponse
        {
            return $this->taskService->getGlobalTaskStats();
        }

    }
