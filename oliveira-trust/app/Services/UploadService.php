<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Upload;
use Illuminate\Support\Facades\Log; 
use Exception;

class UploadService
{
    public function handleUpload(UploadedFile $file)
    {
        try {
            Log::info("Iniciando upload do arquivo: " . $file->getClientOriginalName());

            $path = $file->store('uploads', 'local');
            Log::info("Arquivo salvo em: " . $path);

            $upload = Upload::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'uploaded_at' => now(),
                'TckrSymb' => null,
                'RptDt' => null,
            ]);

            Log::info("Upload registrado no banco de dados: " . $upload->id);

            return $upload;

        } catch (Exception $e) {
            Log::error('Erro ao salvar o arquivo: ' . $e->getMessage());
            throw new Exception('Erro ao salvar o arquivo: ' . $e->getMessage());
        }
    }
}
