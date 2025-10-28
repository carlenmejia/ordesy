<?php

namespace Modules\Subdomain\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class BannedSubdomainRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'banned_subdomain' => 'required'
        ];
    }
}
