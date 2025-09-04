<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
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
            'product_id' => 'exists:products,id',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'expiration_date' => 'required|date|after:today',
            'batch_code' => 'required|string|max:255|unique:batches,batch_code',
        ];
    }

    public function messages()
    {
        return [
            'product_id.exists' => 'O produto selecionado não existe.',
            'quantity.required' => 'A quantidade é obrigatória.',
            'quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'quantity.min' => 'A quantidade deve ser pelo menos 1.',
            'cost_price.required' => 'O preço de custo é obrigatório.',
            'cost_price.numeric' => 'O preço de custo deve ser um número.',
            'cost_price.min' => 'O preço de custo não pode ser negativo.',
            'sale_price.required' => 'O preço de venda é obrigatório.',
            'sale_price.numeric' => 'O preço de venda deve ser um número.',
            'sale_price.min' => 'O preço de venda não pode ser negativo.',
            'expiration_date.required' => 'A data de validade é obrigatória.',
            'expiration_date.date' => 'A data de validade deve ser uma data válida.',
            'expiration_date.after' => 'A data de validade deve ser uma data futura.',
            'batch_code.required' => 'O código do lote é obrigatório.',
            'batch_code.string' => 'O código do lote deve ser uma string.',
            'batch_code.max' => 'O código do lote não pode exceder 255 caracteres.',
            'batch_code.unique' => 'O código do lote deve ser único, já existe algum lote com este código.',
        ];
    }
}
