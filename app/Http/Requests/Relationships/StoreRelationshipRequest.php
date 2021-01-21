<?php

namespace App\Http\Requests\Relationships;

use App\Relationship;
use Illuminate\Foundation\Http\FormRequest;

class StoreRelationshipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Authorize if relationship hadn't already been vreated
     *
     * @return bool
     */
    public function authorize()
    {
        return !Relationship::whereIds($this->user()->id, $this->user_id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => ['required', 'integer']
        ];
    }
}
