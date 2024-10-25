<?php

namespace App\Imports;

use App\Models\Upload;
use Maatwebsite\Excel\Concerns\ToModel;

class UploadImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Upload([
            'file_name' => $row[0],
            // Adicione outras colunas que o arquivo possuir, de acordo com a estrutura
        ]);
    }
}
