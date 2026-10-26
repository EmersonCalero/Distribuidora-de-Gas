<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class AlmacenRequest extends ApiRequest
{
    public function rules(): array
    {
        $r = $this->req();

        return [
            'nombre'    => [$r, 'string', 'max:255'],
            'direccion' => [$r, 'string', 'max:255'],
            'capacidad' => [$r, 'integer', 'min:1'],
        ];
    }

    public function attributes(): array
    {
        return ['direccion' => 'dirección'];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'capacidad.min' => 'La capacidad del almacén debe ser de al menos 1 balón.',
        ]);
    }
}
