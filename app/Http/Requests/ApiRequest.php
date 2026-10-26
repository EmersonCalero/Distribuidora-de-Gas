<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class ApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** 'required' al crear (POST); 'sometimes' al actualizar (PUT/PATCH). */
    protected function req(): string
    {
        return $this->isMethod('post') ? 'required' : 'sometimes';
    }

    public function messages(): array
    {
        return [
            'required'    => 'El campo :attribute es obligatorio.',
            'string'      => 'El campo :attribute debe ser texto.',
            'integer'     => 'El campo :attribute debe ser un número entero.',
            'numeric'     => 'El campo :attribute debe ser un número.',
            'boolean'     => 'El campo :attribute debe ser verdadero o falso.',
            'date'        => 'El campo :attribute debe ser una fecha válida.',
            'date_format' => 'El campo :attribute debe tener el formato :format.',
            'email'       => 'El campo :attribute debe ser un correo electrónico válido.',
            'digits'      => 'El campo :attribute debe tener exactamente :digits dígitos.',
            'exists'      => 'El valor indicado en :attribute no existe en el sistema.',
            'unique'      => 'El valor indicado en :attribute ya está registrado.',
            'in'          => 'El valor indicado en :attribute no es válido.',
            'before'      => 'El campo :attribute debe ser una fecha anterior a hoy.',
            'max.string'  => 'El campo :attribute no debe superar los :max caracteres.',
            'max.numeric' => 'El campo :attribute no debe ser mayor que :max.',
            'min.numeric' => 'El campo :attribute debe ser como mínimo :min.',
        ];
    }
}
