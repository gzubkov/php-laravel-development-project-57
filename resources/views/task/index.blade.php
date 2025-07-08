<x-app-layout>
    <section class="bg-white">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="grid col-span-full">

                
                <h1 class="mb-5">{{ __('app.tasks') }}</h1>

                <div class="w-full flex items-center">
                    <div>
                        {{ html()->form('GET', route('tasks.index'))->open() }}

                        {{ html()->div()->class('flex')->open() }}

                        {{ html()->select('filter[status_id]')->options(['' => __('app.Task_status')] + $taskStatuses)->value(request()->input('filter.status_id', ''))->class('rounded border-gray-300') }}

                        {{ html()->select('filter[created_by_id]')->options(['' => __('app.fields.author')] + $users)->value(request()->input('filter.created_by_id', ''))->class('rounded border-gray-300') }}

                        {{ html()->select('filter[assigned_to_id]')->options(['' => __('app.fields.contractor')] + $users)->value(request()->input('filter.assigned_to_id', ''))->class('rounded border-gray-300') }}

                        {{ html()->submit(__('app.actions.apply'))->class('blue-button ml-2') }}

                        {{ html()->div()->close() }}

                        {{ html()->form()->close() }}
                    </div>


                @can('create', $taskModel)
                    <div class="ml-auto">
                        <a href="{{ route('tasks.create') }}" class="blue-button ml-2">
                            {{ __('app.actions.task.create') }}
                        </a>                    
                    </div>
                @endcan
                </div>

                <table class="mt-4">
                    <thead class="border-b-2 border-solid border-black text-left">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('app.Task_status') }}</th>
                            <th>{{ __('app.fields.name') }}</th>
                            <th>{{ __('app.fields.author') }}</th>
                            <th>{{ __('app.fields.contractor') }}</th>
                            <th>{{ __('app.fields.date_created') }}</th>
                            @canany(['update', 'delete'], $taskModel)
                                <th>{{ __('app.actions.actions') }}</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr class="border-b border-dashed text-left">
                                <td>{{ $task->id }}</td>
                                <td>{{ $task->status->name }}</td>
                                <td><a class="text-blue-600 hover:text-blue-900" href="{{ route('tasks.show', $task) }}">{{ $task->name }}</a></td>
                                <td>{{ $task->creator->name }}</td>
                                <td>{{ $task->contractor?->name ?? '-' }}</td>
                                <td>{{ $task->created_at->format('d.m.Y') }}</td>
                                <td>
                                    @can('delete', $task)
                                        <a data-confirm="{{ __('app.actions.confirm') }}" data-method="delete" rel="nofollow"
                                            class="delete-link" href="{{ route('tasks.destroy', $task) }}">
                                            {{ __('app.actions.delete') }}
                                        </a>
                                    @endcan
                                    @can('update', $task)
                                        <a class="edit-link" href="{{ route('tasks.edit', $task) }}">
                                            {{ __('app.actions.edit') }}
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </section>
</x-app-layout>