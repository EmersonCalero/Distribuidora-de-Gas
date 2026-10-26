<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class AjusteStockRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'balon_id'     => ['required', 'exists:balones,id'],
            'almacen_id'   => ['required', 'exists:almacenes,id'],
            'user_id'      => ['required', 'exists:users,id'],
            'tipo'         => ['required', Rule::in(['entrada', 'salida'])],
            'estado_balon' => ['required', Rule::in(['lleno', 'vacio'])],
            'cantidad'     => ['required', 'integer', 'min:1'],
            'observacion'  => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'balon_id' => 'balón', 'almacen_id' => 'almacén', 'user_id' => 'usuario',
            'estado_balon' => 'estado del balón', 'observacion' => 'motivo del ajuste',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'observacion.required' => 'RN-03: todo ajuste de stock debe registrar su motivo.',
            'tipo.in'              => 'El tipo de ajuste debe ser entrada o salida.',
            'estado_balon.in'      => 'El estado del balón debe ser lleno o vacio.',
        ]);
    }
}
