<?php

namespace App\Http\Requests\Relationships;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRelationshipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $relationship = $this->route('relationship');
        $status_changed = $relationship->status != $this->status;
        return $relationship->hasMember($this->user()) && $status_changed;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['string', 'in:accepted,rejected']
        ];
    }
}
