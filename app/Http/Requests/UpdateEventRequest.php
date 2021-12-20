<?php

namespace App\Http\Requests;

use App\Models\Event;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('event_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:events,name,' . request()->route('event')->id,
            ],
            'date_start' => [
                'date_format:' . config('project.datetime_format'),
                'required',
            ],
            'date_end' => [
                'date_format:' . config('project.datetime_format'),
                'required',
            ],
            'studio' => [
                'array',
            ],
            'studio.*.id' => [
                'integer',
                'exists:studios,id',
            ],
        ];
    }
}
