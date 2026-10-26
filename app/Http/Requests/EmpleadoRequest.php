<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class EmpleadoRequest extends ApiRequest
{
    public function rules(): array
    {
        $r = $this->req();

        return [
            'usuario_id'   => [$r, 'exists:usuarios,id', Rule::unique('empleados', 'usuario_id')->ignore($this->route('empleado'))],
            'turno'        => [$r, Rule::in(['manana', 'tarde', 'noche'])],
            'cargo'        => [$r, 'string', 'max:100'],
            'hora_ingreso' => [$r, 'date_format:H:i'],
            'hora_salida'  => [$r, 'date_format:H:i'],
            'activo'       => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['usuario_id' => 'persona', 'hora_ingreso' => 'hora de ingreso', 'hora_salida' => 'hora de salida'];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'usuario_id.unique' => 'Esa persona ya está registrada como empleado.',
            'turno.in'          => 'El turno debe ser manana, tarde o noche.',
            'hora_ingreso.date_format' => 'La hora de ingreso debe tener el formato HH:MM (por ejemplo, 08:00).',
            'hora_salida.date_format'  => 'La hora de salida debe tener el formato HH:MM (por ejemplo, 16:00).',
        ]);
    }
}
