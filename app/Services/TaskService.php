<?php

    namespace App\Services;

    use App\Models\Task;
    use Carbon\Carbon;
    use Illuminate\Http\JsonResponse;

    class TaskService
    {
        public function getAllTasks(int $userId, string $sortBy, string $sortOrder)
        {
            return Task::getAllTasks($userId, $sortBy, $sortOrder);
        }

        public function getTaskById(int $userId, int $taskId): Task
        {
            return Task::getTaskById($userId, $taskId);
        }

        public function createTask(int $userId, array $data): JsonResponse
        {
            $task = Task::create(
                array_merge($data, [
                    'user_id' => $userId,
                    'status' => Task::STATUS_NEW,
                    'start_at' => Carbon::createFromFormat('d-m-Y H:i', $data['start_at'])->format('Y-m-d H:i:s')
                ])
            );
            return response()->json($task, 201);
        }

        public function updateTask(int $userId, int $taskId, array $data): JsonResponse
        {
            $task = Task::getTaskById($userId, $taskId);

            $allowedTransitions = [
                Task::STATUS_NEW => [Task::STATUS_IN_PROGRESS],
                Task::STATUS_IN_PROGRESS => [Task::STATUS_FINISHED, Task::STATUS_FAILED],
            ];

            if (isset($data['status']) && !in_array($data['status'], $allowedTransitions[$task->status] ?? [])) {
                return response()->json(['error' => 'Invalid status transition.'], 403);
            }

            return $task->updateTask($data);
        }


        public function deleteTask(int $userId, int $taskId): JsonResponse
        {
            $task = Task::getTaskById($userId, $taskId);
            return $task->deleteTask();
        }

        public function deleteAllNewTasks(int $userId): JsonResponse
        {
            return Task::deleteAllNewTasks($userId);
        }

        public function getTaskStatsByUser(int $userId): JsonResponse
        {
            return Task::getTaskStatsByUser($userId);
        }

        public function getGlobalTaskStats(): JsonResponse
        {
            return Task::getGlobalTaskStats();
        }

    }
