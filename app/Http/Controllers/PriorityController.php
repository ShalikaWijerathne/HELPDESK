<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePriorityRequest;
use App\Models\Priority;
use App\Services\AuditLogger;

class PriorityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $priorities = Priority::orderBy('rank')->paginate(20);
        return view('priorities.index', compact('priorities'));
    }

    public function create()
    {
        return view('priorities.form', ['priority' => new Priority()]);
    }

    public function store(StorePriorityRequest $request)
    {
        $priority = Priority::create($request->validated());
        AuditLogger::log('priority.created', $priority, null, ['name' => $priority->name]);

        return redirect()->route('priorities.index')
                         ->with('success', "Priority \"{$priority->name}\" created.");
    }

    public function edit(Priority $priority)
    {
        return view('priorities.form', compact('priority'));
    }

    public function update(StorePriorityRequest $request, Priority $priority)
    {
        $old = $priority->only('name', 'rank');
        $priority->update($request->validated());

        AuditLogger::log('priority.updated', $priority, $old, $priority->only('name', 'rank'));

        return redirect()->route('priorities.index')->with('success', 'Priority updated.');
    }
}
