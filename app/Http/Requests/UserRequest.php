<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UserRequest
 * @package   App\Http\Requests
 * @author    Victor Tores <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserRequest extends FormRequest
{
    /** @var Rule $validationRule */
    private Rule $validationRule;

    /**
     * UserRequest constructor.
     * @param Rule $validationRule
     */
    public function __construct(Rule $validationRule)
    {
        parent::__construct();
        $this->validationRule = $validationRule;
    }

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
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'type' => 'required|string|in:common,shopkeeper',
            'cpf_cnpj' => [
                'required',
                'string',
                $this->validationRule->when($this->input('type') === 'common', ['size:11'], ['size:14']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'O campo :attribute deve ser "common" ou "shopkeeper".',
        ];
    }
}
