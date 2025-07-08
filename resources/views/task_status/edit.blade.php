<x-app-layout>
    <section class="bg-white">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="grid col-span-full">
                <h1 class="mb-5">{{ __('app.actions.task_status.edit') }}</h1>

                {{ html()->modelForm($taskStatus, 'PATCH', route('task_statuses.update', $taskStatus))->class('w-50')->open() }}

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
                <div class="mt-2">
                    {{ html()->submit(__('app.actions.update'))->class("blue-button") }}
                </div>
                {{ html()->div()->close() }}

                {{ html()->closeModelForm() }}
            </div>
        </div>
    </section>
</x-app-layout>