<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BalonRequest extends ApiRequest
{
    public function rules(): array
    {
        $r = $this->req();

        return [
            'codigo'       => [$r, 'string', 'max:20', Rule::unique('balones', 'codigo')->ignore($this->route('balon'))],
            'marca'        => [$r, 'string', 'max:100'],
            'capacidad_kg' => [$r, 'numeric', 'min:0.01', 'max:999.99'],
            'precio_venta' => ['sometimes', 'numeric', 'min:0', 'max:99999999.99'],
            'estado'       => ['sometimes', Rule::in(['activo', 'inactivo'])],
        ];
    }

    public function attributes(): array
    {
        return ['codigo' => 'código', 'capacidad_kg' => 'capacidad en kg', 'precio_venta' => 'precio de venta'];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'codigo.unique' => 'Ya existe un balón con ese código.',
            'estado.in'     => 'El estado del balón debe ser activo o inactivo.',
        ]);
    }
}
