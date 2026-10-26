<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ClienteRequest extends ApiRequest
{
    public function rules(): array
    {
        $r = $this->req();

        return [
            'usuario_id'          => [$r, 'exists:usuarios,id', Rule::unique('clientes', 'usuario_id')->ignore($this->route('cliente'))],
            'tipo_cliente'        => [$r, Rule::in(['residencial', 'comercial', 'industrial'])],
            'es_frecuente'        => ['sometimes', 'boolean'],
            'zona'                => [$r, 'string', 'max:100'],
            'direccion'           => [$r, 'string', 'max:255'],
            'dia_compra_habitual' => ['nullable', 'string', 'max:20'],
            'notas'               => ['nullable', 'string'],
            'activo'              => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'usuario_id' => 'persona', 'tipo_cliente' => 'tipo de cliente', 'es_frecuente' => 'cliente frecuente',
            'direccion' => 'dirección de entrega', 'dia_compra_habitual' => 'día de compra habitual',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'usuario_id.exists' => 'La persona indicada no está registrada en usuarios.',
            'usuario_id.unique' => 'Esa persona ya está registrada como cliente.',
            'tipo_cliente.in'   => 'El tipo de cliente debe ser residencial, comercial o industrial.',
        ]);
    }
}
