<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\IndexTaskRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Label;
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
            ->orderBy('id', 'asc')
            ->paginate(15);

        $filter = $request->filter ?? [];

        return view('task.index', compact('tasks', 'taskModel', 'taskStatuses', 'users', 'filter'));
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
        $labels = Label::getLabels();

        return view('task.create', compact('task', 'users', 'taskStatuses', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $data = $request->validated();

        $task = new Task($data);
        $task->created_by_id = Auth::id();
        $task->save();

        if (
            array_key_exists('labels', $data)
            && count($data['labels']) > 0
        ) {
            $task->labels()->sync($data['labels']);
        }

        flash(__('app.messages.task.create_success'))->success();

        // Редирект на указанный маршрут
        return redirect()
            ->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load('labels');
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
        $labels = Label::getLabels();

        $selectedLabels = $task->labels->pluck('id')->toArray();

        return view('task.edit', compact('task', 'users', 'taskStatuses', 'labels', 'selectedLabels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', Task::class);

        $data = $request->validated();

        $task->fill($data);
        $task->save();

        $task->labels()->sync($data['labels'] ?? []);

        flash(__('app.messages.task.update_success'))->success();

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
        flash(__('app.messages.task.delete_success'))->success();

        return redirect()
            ->route('tasks.index');
    }
}
