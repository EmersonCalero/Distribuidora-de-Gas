<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RutaEntregaRequest extends ApiRequest
{
    public function rules(): array
    {
        $r = $this->req();

        return [
            'numero_ruta'           => [$r, 'string', 'max:20', Rule::unique('rutas_entrega', 'numero_ruta')->ignore($this->route('rutaEntrega'))],
            'zona'                  => [$r, 'string', 'max:100'],
            'ubicaciones_incluidas' => [$r, 'string'],
            'vehiculo_id'           => ['nullable', 'exists:vehiculos,id'],
            'empleado_id'           => ['nullable', Rule::exists('empleados', 'id')->where('activo', true)],
            'fecha_inicio'          => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'numero_ruta' => 'número de ruta', 'ubicaciones_incluidas' => 'ubicaciones incluidas',
            'vehiculo_id' => 'vehículo', 'empleado_id' => 'conductor', 'fecha_inicio' => 'fecha y hora de inicio',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'numero_ruta.unique' => 'Ya existe una ruta con ese número.',
            'empleado_id.exists' => 'El conductor indicado no existe o está inactivo.',
        ]);
    }
}
