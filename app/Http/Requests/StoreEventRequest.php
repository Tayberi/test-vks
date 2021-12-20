<?php

namespace App\Http\Requests;

use App\Models\Event;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEventRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('event_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:events',
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
