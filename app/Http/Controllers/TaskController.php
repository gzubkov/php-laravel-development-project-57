<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\IndexTaskRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(IndexTaskRequest $request)
    {
        $taskModel = new Task();
        $taskStatuses = TaskStatus::pluck('name', 'id')->all();
        $users = User::pluck('name', 'id')->all();
        
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id')->ignore(null),
                AllowedFilter::exact('created_by_id')->ignore(null),
                AllowedFilter::exact('assigned_to_id')->ignore(null),
            ])
            ->allowedSorts(['id', 'name', 'status_id', 'created_by_id', 'assigned_to_id'])
            ->with(['status', 'creator', 'contractor']) 
            ->defaultSort('id')
            ->paginate(15);

        $filter = $request->filter ?? [];

        return view('task.index', compact('tasks', 'taskModel', 'taskStatuses', 'users', 'filter'));
        
        
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id')->ignore(null),
                AllowedFilter::exact('created_by_id')->ignore(null),
                AllowedFilter::exact('assigned_to_id')->ignore(null),
            ])
            ->allowedSorts('id')
            ->defaultSort('id')
            ->paginate(15)
            ->appends(request()->query());

        return view('tasks.index', compact('tasks', 'taskModel', 'users', 'taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Task::class);

        $task = new Task();
        $taskStatuses = TaskStatus::getStatuses();
        $users = User::pluck('name', 'id')->all();
        
        return view('task.create', compact('task', 'users', 'taskStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $data = $request->validated();
        
        $task = new Task($data);
        $task->created_by_id = Auth::id();
        $task->save();

        flash(__('app.messages.create_success', ['module' => __('app.task')]))->success();

        // Редирект на указанный маршрут
        return redirect()
            ->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //$task->load('labels');
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', Task::class);

        $taskStatuses = TaskStatus::getStatuses();
        $users = User::pluck('name', 'id')->all();
        
        return view('task.edit', compact('task', 'users', 'taskStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskRequest $request, Task $task)
    {
        $this->authorize('update', Task::class);

        $data = $request->validated();
        
        $task->fill($data);
        $task->save();

        //$labels = Arr::get($data, 'label', []);
        //$task->labels()->sync($labels);
        
        flash(__('app.messages.update_success', ['module' => __('app.task')]))->success();

        return redirect()
            ->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();
        flash(__('app.messages.delete_success', ['module' => __('app.task')]))->success();
        
        return redirect()
            ->route('tasks.index');
    }
}
