<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskStatusRequest;
use App\Models\TaskStatus;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TaskStatusController extends Controller
{
    use AuthorizesRequests;

    
    /*
    public function __construct() {
        $this->authorizeResource(TaskStatus::class, 'taskStatus');
        // Авторизация всего контроллера целиком
    }
    */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskStatuses = TaskStatus::orderBy('id', 'asc')->get();
        $taskStatusModel = new TaskStatus();
        return view('task_status.index', compact('taskStatuses', 'taskStatusModel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', TaskStatus::class);

        $taskStatus = new TaskStatus();
        return view('task_status.create', compact('taskStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskStatusRequest $request)
    {
        $this->authorize('create', TaskStatus::class);

        $data = $request->validated();
        $taskStatus = new TaskStatus($data);
        $taskStatus->save();

        flash(__('app.messages.create_success', ['module' => __('app.task_status')]))->success();

        // Редирект на указанный маршрут
        return redirect()
            ->route('task_statuses.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskStatus $taskStatus)
    {
        $this->authorize('update', $taskStatus);
        
        return view('task_status.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskStatusRequest $request, TaskStatus $taskStatus)
    {
        $this->authorize('update', $taskStatus);
        
        $data = $request->validated();
        $taskStatus->name = $data['name'];
        $taskStatus->save();

        flash(__('app.messages.update_success', ['module' => __('app.task_status')]))->success();

        return redirect()
            ->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $taskStatus)
    {
        $this->authorize('delete', $taskStatus);
        
        $tasksCount = $taskStatus->tasks()->getQuery()->count();
        if ($tasksCount > 0) {
            flash(__('app.messages.delete_error', ['module' => __('app.task_status')]))->success();
        } else {
            $taskStatus->delete();
            flash(__('app.messages.delete_success', ['module' => __('app.task_status')]))->success();
        }

        return redirect()
            ->route('task_statuses.index');
    }
}
