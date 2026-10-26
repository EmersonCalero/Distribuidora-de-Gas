<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class EntregaRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'pedido_id'          => ['required', 'exists:pedidos,id'],
            'balones_entregados' => ['required', 'integer', 'min:1'],
            'balones_retornados' => ['required', 'integer', 'min:0'], // RN-06: siempre se informa
            'observaciones'      => ['nullable', 'string', 'max:255'],
            'user_id'            => ['required', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'pedido_id' => 'pedido', 'balones_entregados' => 'balones entregados',
            'balones_retornados' => 'balones vacíos retornados', 'user_id' => 'usuario que registra',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'balones_retornados.required' => 'RN-06: debe registrarse la cantidad de balones vacíos que retornan (puede ser 0).',
        ]);
    }
}
