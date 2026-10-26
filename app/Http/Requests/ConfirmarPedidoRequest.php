<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ConfirmarPedidoRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'almacen_id' => ['required', 'exists:almacenes,id'],
            'user_id'    => ['required', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return ['almacen_id' => 'almacén de origen', 'user_id' => 'usuario que confirma'];
    }
}
