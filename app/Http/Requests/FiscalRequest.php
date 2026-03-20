<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FiscalRequest extends FormRequest
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
        $isUpdate = $this->method() === 'PUT' || $this->method() === 'PATCH';

        return [
            'product_id' => $isUpdate 
                ? 'sometimes|exists:products,id'
                : 'required|exists:products,id',

            'cProd' => 'nullable|string|max:60',
            'cEAN' => 'nullable|string|min:8|max:14',
            'xProd' => 'nullable|string|max:120',
            'NCM' => 'nullable|string|size:8',
            'CEST' => 'nullable|string|size:7',
            'CFOP' => 'nullable|string|size:4',
            'cEANTrib' => 'nullable|string|min:8|max:14',
            'CST' => 'nullable|string|min:2|max:3',
            'pST' => 'nullable|numeric|min:0|max:100',
        ];
    }
    public function attributes()
    {
        return [
            'cProd' => 'Código do Produto',
            'cEAN' => 'Código EAN',
            'xProd' => 'Descrição do Produto',
            'NCM' => 'NCM',
            'CEST' => 'CEST',
            'CFOP' => 'CFOP',
            'cEANTrib' => 'EAN tributável',
            'CST' => 'CST',
            'pST' => 'Percentual de ST',
        ];
    }
    public function messages()
    {
        return [
            'product_id.required' => 'O produto é obrigatório.',
            'product_id.exists' => 'O produto selecionado não existe.',

            'cProd.max' => 'O código do produto deve ter no máximo 60 caracteres.',

            'cEAN.min' => 'O código EAN deve ter no mínimo 8 dígitos.',
            'cEAN.max' => 'O código EAN deve ter no máximo 14 dígitos.',

            'xProd.max' => 'A descrição do produto deve ter no máximo 120 caracteres.',

            'NCM.size' => 'O NCM deve conter exatamente 8 dígitos.',
            'NCM.regex' => 'O NCM deve conter apenas números.',

            'CEST.size' => 'O CEST deve conter exatamente 7 dígitos.',
            'CEST.regex' => 'O CEST deve conter apenas números.',

            'CFOP.size' => 'O CFOP deve conter exatamente 4 dígitos.',
            'CFOP.regex' => 'O CFOP deve conter apenas números.',

            'cEANTrib.min' => 'O EAN tributável deve ter no mínimo 8 dígitos.',
            'cEANTrib.max' => 'O EAN tributável deve ter no máximo 14 dígitos.',

            'CST.min' => 'O CST deve ter no mínimo 2 caracteres.',
            'CST.max' => 'O CST deve ter no máximo 3 caracteres.',

            'pST.numeric' => 'O percentual de ST deve ser um número.',
            'pST.min' => 'O percentual de ST não pode ser negativo.',
            'pST.max' => 'O percentual de ST não pode ser maior que 100.',
        ];
    }
}
