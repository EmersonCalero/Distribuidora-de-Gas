<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class PedidoRequest extends ApiRequest
{
    public function rules(): array
    {
        $r = $this->req();

        return [
            'cliente_id'  => [$r, 'exists:clientes,id'],
            'empleado_id' => [$r, Rule::exists('empleados', 'id')->where('activo', true)],
            'balon_id'    => [$r, 'exists:balones,id'],
            'cantidad'    => [$r, 'integer', 'min:1', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return ['cliente_id' => 'cliente', 'empleado_id' => 'empleado', 'balon_id' => 'balón'];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'empleado_id.exists' => 'El empleado indicado no existe o está inactivo.',
            'cantidad.min'       => 'El pedido debe tener al menos 1 balón.',
            'cantidad.max'       => 'Un pedido no puede superar los 1000 balones.',
        ]);
    }
}
