<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Models\Label;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LabelController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = Label::orderBy('id', 'asc')->get();
        $labelModel = new Label();
        return view('label.index', compact('labels', 'labelModel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Label::class);

        $label = new Label();
        return view('label.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabelRequest $request)
    {
        $this->authorize('create', Label::class);

        $data = $request->validated();
        $label = new Label($data);
        $label->save();

        flash(__('app.messages.label.create_success'))->success();

        // Редирект на указанный маршрут
        return redirect()
            ->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label)
    {
        $this->authorize('update', $label);
        
        return view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabelRequest $request, Label $label)
    {
        $this->authorize('update', $label);

        $data = $request->validated();
        $label->fill($data);
        $label->save();

        flash(__('app.messages.label.update_success'))->success();

        return redirect()
            ->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        $this->authorize('delete', $label);

        if ($label->tasks()->exists()) {
            flash(__('app.messages.label.delete_failed'))->error();
        } else {
            $label->delete();
            flash(__('app.messages.label.delete_success'))->success();
        }

        return redirect()
            ->route('labels.index');
    }
}
