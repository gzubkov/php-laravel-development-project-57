<x-app-layout>
    <section class="bg-white">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="grid col-span-full">
                <h1 class="mb-5">{{ __('app.actions.create_task') }}</h1>

                {{ html()->modelForm($task, 'POST', route('tasks.store'))->class('w-50')->open() }}

                {{ html()->div()->class('flex flex-col')->open() }}
                <div>
                    {{ html()->label(__('app.fields.name'), 'name') }}
                </div>
                <div class="mt-2">
                    {{ html()->text('name')->class('form-field')->classIf($errors->has('name'), 'border-rose-600') }}
                    @if ($errors->has('name'))
                        <p class="text-rose-600">{{ $errors->first('name') }}</p>
                    @endif
                </div>
                <div>
                    {{ html()->label(__('app.fields.description'), 'description') }}
                </div>
                <div class="mt-2">
                    {{ html()->textarea('description')->class('form-field h-32')->classIf($errors->has('description'), 'border-rose-600') }}
                    @if ($errors->has('description'))
                        <p class="text-rose-600">{{ $errors->first('description') }}</p>
                    @endif
                </div>
                <div>
                    {{ html()->label(__('app.Task_status'), 'status_id') }}
                </div>
                <div class="mt-2">
                    {{ html()->select('status_id')->options(['' => ''] + $taskStatuses)->value(old('status_id', ''))->class('form-field')->classIf($errors->has('status_id'), 'border-rose-600') }}
                    @if ($errors->has('status_id'))
                        <p class="text-rose-600">{{ $errors->first('status_id') }}</p>
                    @endif
                </div>

                <div class="mt-2">
                    {{ html()->label(__('app.fields.contractor'), 'assigned_to_id') }}
                </div>
                <div>
                    {{ html()->select('assigned_to_id')->options(['' => ''] + $users)->value(old('assigned_to_id', ''))->class('form-field') }}
                </div>

                <div class="mt-2">
                    {{ html()->submit(__('app.actions.create'))->class("bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded") }}
                </div>
                {{ html()->div()->close() }}

                {{ html()->closeModelForm() }}
            </div>
        </div>
    </section>
</x-app-layout>