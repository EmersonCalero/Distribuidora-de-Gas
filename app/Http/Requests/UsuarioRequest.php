<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UsuarioRequest extends ApiRequest
{
    public function rules(): array
    {
        $r = $this->req();
        $actual = $this->route('usuario');

        return [
            'nombre'           => [$r, 'string', 'max:50'],
            'apellido'         => [$r, 'string', 'max:50'],
            'dni'              => [$r, 'digits:8', Rule::unique('usuarios', 'dni')->ignore($actual)],
            'telefono'         => [$r, 'digits:9', Rule::unique('usuarios', 'telefono')->ignore($actual)],
            'direccion'        => [$r, 'string', 'max:100'],
            'genero'           => [$r, Rule::in(['M', 'F'])],
            'fecha_nacimiento' => [$r, 'date', 'before:today'],
            'email'            => [$r, 'email', 'max:100', Rule::unique('usuarios', 'email')->ignore($actual)],
        ];
    }

    public function attributes(): array
    {
        return [
            'dni' => 'DNI', 'telefono' => 'teléfono', 'direccion' => 'dirección',
            'genero' => 'género', 'fecha_nacimiento' => 'fecha de nacimiento', 'email' => 'correo electrónico',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'dni.digits'      => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique'      => 'Ya existe una persona registrada con ese DNI.',
            'telefono.digits' => 'El teléfono debe tener 9 dígitos.',
            'telefono.unique' => 'Ese teléfono ya pertenece a otra persona.',
            'email.unique'    => 'Ese correo electrónico ya está registrado.',
        ]);
    }
}
