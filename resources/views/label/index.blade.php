<x-app-layout>
    <section class="bg-white">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="grid col-span-full">

                
                <h1 class="mb-5">{{ __('app.labels') }}</h1>

                @can('create', $labelModel)
                    <div>
                        <a href="{{ route('labels.create') }}" class="blue-button">
                            {{ __('app.actions.label.create') }}
                        </a>
                    </div>
                @endcan

                <table class="mt-4">
                    <thead class="border-b-2 border-solid border-black text-left">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('app.fields.name') }}</th>
                            <th>{{ __('app.fields.description') }}</th>
                            <th>{{ __('app.fields.date_created') }}</th>
                            @canany(['update', 'delete'], $labelModel)
                                <th>{{ __('app.actions.actions') }}</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($labels as $label)
                            <tr class="border-b border-dashed text-left">
                                <td>{{ $label->id }}</td>
                                <td>{{ $label->name }}</td>
                                <td>{{ $label->description }}</td>
                                <td>{{ $label->created_at->format('d.m.Y') }}</td>
                                <td>
                                    @can('delete', $label)
                                        <a data-confirm="{{ __('app.actions.confirm') }}" data-method="delete" rel="nofollow"
                                            class="delete-link" href="{{ route('labels.destroy', $label) }}">
                                            {{ __('app.actions.delete') }}
                                        </a>
                                    @endcan
                                    @can('update', $label)
                                        <a class="edit-link" href="{{ route('labels.edit', $label) }}">
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