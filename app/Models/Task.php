<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\JsonResponse;

    class Task extends Model
    {
        use HasFactory;

        protected $fillable = [
            'title',
            'description',
            'user_id',
            'status',
            'start_at'
        ];

        public const STATUS_NEW = 'New';
        public const STATUS_IN_PROGRESS = 'In Progress';
        public const STATUS_FAILED = 'Failed';
        public const STATUS_FINISHED = 'Finished';

        public static array $allowedStatuses = [
            self::STATUS_NEW,
            self::STATUS_IN_PROGRESS,
            self::STATUS_FAILED,
            self::STATUS_FINISHED,
        ];

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }

        public function scopeFilterAndSort(Builder $query, array $filters): Builder
        {
            if (!empty($filters['title'])) {
                $query->where('title', 'like', '%' . $filters['title'] . '%');
            }

            if (!empty($filters['status']) && in_array($filters['status'], self::$allowedStatuses)) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['sort_by']) && in_array($filters['sort_by'], ['title', 'status'])) {
                $sortOrder = $filters['sort_order'] ?? 'asc';
                $query->orderBy($filters['sort_by'], $sortOrder);
            }

            return $query;
        }

        public static function getAllTasks(int $userId, array $filters)
        {
            return self::where('user_id', $userId)
                ->filterAndSort($filters)
                ->paginate(10);
        }

        public static function getTaskById(int $userId, int $taskId): self
        {
            return self::where('user_id', $userId)->findOrFail($taskId);
        }

        public function updateTask(array $data): JsonResponse
        {
            try {
                $this->update($data);
                return response()->json($this, 200);
            } catch (QueryException $e) {
                return response()->json(['error' => 'Failed to update task.', 'message' => $e->getMessage()], 500);
            }
        }

        public static function deleteAllNewTasks(int $userId): JsonResponse
        {
            try {
                $deletedCount = self::where('user_id', $userId)
                    ->where('status', self::STATUS_NEW)
                    ->delete();

                return response()->json(['message' => "$deletedCount new tasks deleted successfully."], 200);
            } catch (QueryException $e) {
                return response()->json(['error' => 'Failed to delete new tasks.', 'message' => $e->getMessage()], 500);
            }
        }

        public static function getTaskStatsByUser(int $userId): JsonResponse
        {
            $stats = self::where('user_id', $userId)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $stats = array_merge([
                self::STATUS_NEW => 0,
                self::STATUS_IN_PROGRESS => 0,
                self::STATUS_FAILED => 0,
                self::STATUS_FINISHED => 0,
            ], $stats);

            return response()->json(['stats' => $stats], 200);
        }

        public static function getGlobalTaskStats(): JsonResponse
        {
            $stats = self::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            return response()->json(['stats' => $stats], 200);

        }
    }
