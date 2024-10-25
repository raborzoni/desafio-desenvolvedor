<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Retorne true para permitir que qualquer usuário faça a requisição
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'file' => 'required|mimes:csv,xlsx,xls|max:51200',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'O arquivo é obrigatório.',
            'file.mimes' => 'O arquivo deve ser um dos seguintes tipos: CSV, XLSX, XLS.',
            'file.max' => 'O arquivo não pode ser maior que 50MB.',
        ];
    }
}
