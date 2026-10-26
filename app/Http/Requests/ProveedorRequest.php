<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ProveedorRequest extends ApiRequest
{
    public function rules(): array
    {
        $r = $this->req();
        $actual = $this->route('proveedor');

        return [
            'ruc'          => [$r, 'digits:11', Rule::unique('proveedores', 'ruc')->ignore($actual)],
            'razon_social' => [$r, 'string', 'max:255'],
            'telefono'     => [$r, 'digits:9', Rule::unique('proveedores', 'telefono')->ignore($actual)],
            'direccion'    => [$r, 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['ruc' => 'RUC', 'razon_social' => 'razón social', 'telefono' => 'teléfono', 'direccion' => 'dirección'];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'ruc.digits' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.unique' => 'Ya existe un proveedor con ese RUC.',
        ]);
    }
}
