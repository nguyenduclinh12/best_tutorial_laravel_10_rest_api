<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTask;
use App\Http\Requests\UpdateTask;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    public function __construct(){
        $this->authorizeResource(Task::class,'task');
    }
    public function index()
    {

        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters('is_done')
            ->defaultSort('-created_at')
            ->allowedSorts('title', 'is_done', 'created_at')
            ->paginate();
        return new TaskCollection($tasks);
    }
    public function show(Request $request, Task $task)
    {
        return new TaskResource($task);
    }
    public function store(StoreTask $request)
    {
        $validated = $request->validated();
        // $task = Task::create($validated);
        $task = Auth::user()->tasks()->create($validated);
        return new TaskResource($task);
    }
    public function update(UpdateTask $request, Task $task)
    {
        $validated = $request->validated();
        $task->update($validated);
        return new TaskResource($task);
    }
    public function destroy(Request $request, Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
