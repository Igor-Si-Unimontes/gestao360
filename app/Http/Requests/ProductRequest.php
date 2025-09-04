<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|integer|unique:products,code',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'returnable_product' => 'boolean',
            'description' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'O nome do produto é obrigatório.',
            'code.required' => 'O código do produto é obrigatório.',
            'code.integer' => 'O código do produto deve ser um número inteiro.',
            'code.unique' => 'O código do produto deve ser único, já existe algum produto com este código.',
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'supplier_id.required' => 'O fornecedor é obrigatório.',
            'supplier_id.exists' => 'O fornecedor selecionado não existe.',
            'returnable_product.boolean' => 'O campo de produto retornável deve ser sim ou não.',
            'description.string' => 'A descrição deve ser uma string.',
        ];
    }
}
