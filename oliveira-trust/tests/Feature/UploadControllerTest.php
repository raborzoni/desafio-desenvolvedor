<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Upload;

class UploadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_file_successfully()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('test_file.csv', 100, 'text/csv');

        $response = $this->postJson('/api/upload', [
            'file' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Arquivo enviado com sucesso!',
            ]);

        Storage::disk('local')->assertExists('uploads/' . $file->hashName());
    }

    public function test_upload_without_file_should_fail()
    {
        $response = $this->postJson('/api/upload', []);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'file' => ['O arquivo é obrigatório.']
                ]
            ]);
    }

    public function test_upload_invalid_file_type_should_fail()
    {
        $file = UploadedFile::fake()->create('test_file.txt', 100, 'text/plain');

        $response = $this->postJson('/api/upload', [
            'file' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'file' => ['O arquivo deve ser um dos seguintes tipos: CSV, XLSX, XLS.']
                ]
            ]);
    }

    /**
     * Teste para buscar histórico de uploads.
     */
    public function test_get_upload_history()
    {
        Upload::factory()->create(['file_name' => 'file1.csv', 'uploaded_at' => now()]);
        Upload::factory()->create(['file_name' => 'file2.csv', 'uploaded_at' => now()->subDay()]);

        $response = $this->getJson('/api/history');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'file_name', 'uploaded_at']
            ]);
    }

    /**
     * Teste para buscar histórico de uploads filtrando por nome de arquivo.
     */
    public function test_get_upload_history_by_filename()
    {
        Upload::factory()->create(['file_name' => 'file1.csv', 'uploaded_at' => now()]);
        Upload::factory()->create(['file_name' => 'file2.csv', 'uploaded_at' => now()->subDay()]);

        $response = $this->getJson('/api/history?filename=file1.csv');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['file_name' => 'file1.csv']);
    }

    /**
     * Teste para buscar conteúdo do arquivo com parâmetros TckrSymb e RptDt.
     */
    public function test_search_content_with_parameters()
    {
        Upload::factory()->create(["TckrSymb" => "AMZO34", "RptDt" => "2024-08-22"]);

        $response = $this->getJson('/api/search?TckrSymb=AMZO34&RptDt=2024-08-22');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'TckrSymb' => 'AMZO34',
                'RptDt' => '2024-08-22'
            ]);
    }

    /**
     * Teste para buscar conteúdo do arquivo sem parâmetros (retorno paginado).
     */
    public function test_search_content_without_parameters_should_return_paginated()
    {
    Upload::factory()->count(15)->create();

    $response = $this->getJson('/api/search');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['TckrSymb', 'RptDt', 'MktNm', 'SctyCtgyNm', 'ISIN', 'CrpnNm'],
                 ],
                 'links', 
                 'current_page', 
                 'last_page',
                 'per_page',
                 'total',
                 'first_page_url',
                 'last_page_url',
                 'next_page_url',
                 'prev_page_url',
             ]);
    }
}
