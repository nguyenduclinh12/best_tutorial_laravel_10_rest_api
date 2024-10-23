<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }
    public function index()
    {
        return Project::all();
        return Project::where('creator_id', Auth::user())->get();
        // yêu cầu đăng nhập . sẽ lấy danh sách project của user đã đăng nhập
        return new ProjectCollection(Auth::user()->projects()->paginate());
        $projects = QueryBuilder::for(Project::class)
            ->allowedIncludes('tasks')
            ->paginate();
        dd($projects);
        return new ProjectCollection($projects);
    }
    public function show(Request $request, Project $project)
    {
        return (new ProjectResource($project))
            ->load('tasks')
            ->load('members');
    }
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        $project = Auth::user()->projects()->create($validated);
        return new ProjectResource($project);
    }
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project->update($validated);
        return new ProjectResource($project);
    }
    public function destroy(Request $request, Project $project)
    {
        $project->delete();
        return response()->noContent();
    }
}
