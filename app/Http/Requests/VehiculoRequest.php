<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class VehiculoRequest extends ApiRequest
{
    public function rules(): array
    {
        $r = $this->req();

        return [
            'placa'             => [$r, 'string', 'max:10', Rule::unique('vehiculos', 'placa')->ignore($this->route('vehiculo'))],
            'marca'             => [$r, 'string', 'max:100'],
            'modelo'            => [$r, 'string', 'max:100'],
            'capacidad_balones' => [$r, 'integer', 'min:1'],
            'estado'            => ['sometimes', Rule::in(['disponible', 'en_ruta', 'mantenimiento'])],
        ];
    }

    public function attributes(): array
    {
        return ['capacidad_balones' => 'capacidad de balones'];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'placa.unique' => 'Ya existe un vehículo con esa placa.',
            'capacidad_balones.min' => 'La capacidad debe ser de al menos 1 balón.',
        ]);
    }
}
