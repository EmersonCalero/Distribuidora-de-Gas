<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CompraRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'proveedor_id'    => ['required', 'exists:proveedores,id'],
            'balon_id'        => ['required', 'exists:balones,id'],
            'almacen_id'      => ['required', 'exists:almacenes,id'],
            'cantidad'        => ['required', 'integer', 'min:1'],
            'precio_unitario' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'user_id'         => ['required', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'proveedor_id' => 'proveedor', 'balon_id' => 'balón', 'almacen_id' => 'almacén',
            'precio_unitario' => 'precio unitario', 'user_id' => 'usuario que registra',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'cantidad.min' => 'Debe comprarse al menos 1 balón.',
        ]);
    }
}
