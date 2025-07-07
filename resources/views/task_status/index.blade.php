<x-app-layout>
    <section class="bg-white">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="grid col-span-full">

                
                <h1 class="mb-5">{{ __('app.task_statuses') }}</h1>

                @can('create', $taskStatusModel)
                    <div>
                        <a href="{{ route('task_statuses.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('app.actions.create_module', ['module' => __('app.task_status')]) }}
                        </a>
                    </div>
                @endcan

                <table class="mt-4">
                    <thead class="border-b-2 border-solid border-black text-left">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('app.fields.name') }}</th>
                            <th>{{ __('app.fields.date_created') }}</th>
                            @canany(['update', 'delete'], $taskStatusModel)
                                <th>{{ __('app.actions.actions') }}</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taskStatuses as $taskStatus)
                            <tr class="border-b border-dashed text-left">
                                <td>{{ $taskStatus->id }}</td>
                                <td>{{ $taskStatus->name }}</td>
                                <td>{{ $taskStatus->created_at->format('d.m.Y') }}</td>
                                <td>
                                    @can('delete', $taskStatus)
                                        <a data-confirm="{{ __('app.actions.confirm') }}" data-method="delete" rel="nofollow"
                                            class="text-red-600 hover:text-red-900" href="{{ route('task_statuses.destroy', $taskStatus) }}">
                                            {{ __('app.actions.delete') }}
                                        </a>
                                    @endcan
                                    @can('update', $taskStatus)
                                        <a class="text-blue-600 hover:text-blue-900" href="{{ route('task_statuses.edit', $taskStatus) }}">
                                            {{ __('app.actions.edit') }}
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-app-layout>