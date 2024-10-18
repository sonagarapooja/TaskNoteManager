<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

class TaskController extends Controller
{
    /**
     * Retrieves all tasks with associated notes.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Task::with('notes');

            // Apply filters if passed in the request
            if ($request->has('filter.status')) {
                $query->where('status', $request->input('filter.status'));
            }

            if ($request->has('filter.due_date')) {
                $query->where('due_date', $request->input('filter.due_date'));
            }

            if ($request->has('filter.priority')) {
                $query->where('priority', $request->input('filter.priority'));
            }

            // Filter tasks that have at least one note
            if ($request->has('filter.notes') && $request->input('filter.notes') === 'R') {
                $query->whereHas('notes');
            }

            $tasks = $query->orderByRaw("CASE WHEN priority = 'High' THEN 1 ELSE 2 END")
                ->orderBy('notes_count', 'desc')
                ->withCount('notes')
                ->get();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Tasks retrieved successfully',
                'data' => $tasks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Creates a task with multiple notes.
     *
     * @param Request  $request
     * @return JsonResponse
     *
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'due_date' => 'required|date',
                'status' => 'required|in:New,Incomplete,Complete',
                'priority' => 'required|in:High,Medium,Low',
                'notes' => 'nullable|array', // notes is required and must be an array
                'notes.*.subject' => 'nullable|string', // subject is optional
                'notes.*.attachment' => 'nullable|array', // Allow attachments to be an array
                'notes.*.attachment.*' => 'file', // Allow multiple file uploads for attachments     
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors occurred',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $task = Task::create([
                'user_id'     => Auth::id(),
                'subject'     => $request->subject,
                'description' => $request->description,
                'start_date'  => $request->start_date,
                'due_date'    => $request->due_date,
                'status'      => $request->status,
                'priority'    => $request->priority,
            ]);

            if ($request->notes) {
                foreach ($request->notes as $note) {
                    $attachments = [];

                    if (isset($note['attachment'])) {
                        foreach ($note['attachment'] as $file) {
                            $path = $file->store('attachments'); // Store the file and save the path
                            $attachments[] = $path;
                        }
                    }

                    $task->notes()->create([
                        'task_id' => $task->id,
                        'subject' => $note['subject'],
                        'note' => $note['note'],
                        'attachment' => (!empty($attachments)) ? json_encode($attachments) : null,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => $task, // Optional, Include the created task in the response
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
