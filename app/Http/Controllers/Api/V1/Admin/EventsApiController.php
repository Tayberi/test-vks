<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\Admin\EventResource;
use App\Models\Event;
use App\Models\Studio;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('event_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EventResource(Event::with(['studio'])->advancedFilter());
    }

    public function store(StoreEventRequest $request)
    {
        $event = Event::create($request->validated());
        $event->studio()->sync($request->input('studio.*.id', []));

        return (new EventResource($event))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function create()
    {
        abort_if(Gate::denies('event_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'meta' => [
                'studio' => Studio::get(['id', 'name']),
            ],
        ]);
    }

    public function show(Event $event)
    {
        abort_if(Gate::denies('event_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EventResource($event->load(['studio']));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());
        $event->studio()->sync($request->input('studio.*.id', []));

        return (new EventResource($event))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function edit(Event $event)
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'data' => new EventResource($event->load(['studio'])),
            'meta' => [
                'studio' => Studio::get(['id', 'name']),
            ],
        ]);
    }

    public function destroy(Event $event)
    {
        abort_if(Gate::denies('event_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
