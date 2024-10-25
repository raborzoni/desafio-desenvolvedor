<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Upload;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Documentação da API Oliveira Trust",
 *     description="Esta é a documentação da API Oliveira Trust desenvolvida para upload, histórico de arquivos e busca de dados.",
 *     @OA\Contact(
 *         email="seu_email@exemplo.com"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Upload",
 *     type="object",
 *     title="Upload",
 *     description="Modelo de um upload",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID do upload"
 *     ),
 *     @OA\Property(
 *         property="file_name",
 *         type="string",
 *         description="Nome do arquivo enviado"
 *     ),
 *     @OA\Property(
 *         property="file_path",
 *         type="string",
 *         description="Caminho do arquivo armazenado"
 *     ),
 *     @OA\Property(
 *         property="uploaded_at",
 *         type="string",
 *         format="date-time",
 *         description="Data e hora do upload"
 *     ),
 *     @OA\Property(
 *         property="TckrSymb",
 *         type="string",
 *         description="Símbolo do ticker (se aplicável)"
 *     ),
 *     @OA\Property(
 *         property="RptDt",
 *         type="string",
 *         format="date",
 *         description="Data do relatório (se aplicável)"
 *     )
 * )
 */
class UploadController extends Controller
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * @OA\Post(
     *     path="/api/upload",
     *     summary="Fazer o upload de um arquivo",
     *     description="Este endpoint faz o upload de um arquivo no formato CSV ou Excel.",
     *     operationId="uploadFile",
     *     tags={"Uploads"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Arquivo CSV ou Excel para ser enviado."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Arquivo enviado com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Arquivo enviado com sucesso!"),
     *             @OA\Property(property="upload", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na requisição.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Nenhum arquivo foi enviado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro no servidor.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao processar o upload do arquivo."),
     *             @OA\Property(property="details", type="string")
     *         )
     *     )
     * )
     */

    public function upload(UploadFileRequest $request)
    {
        try {
            if (!$request->hasFile('file')) {
                throw new HttpResponseException(
                    response()->json(['error' => 'Nenhum arquivo foi enviado.'], 400)
                );
            }

            $file = $request->file('file');

            if (!($file instanceof \Illuminate\Http\UploadedFile)) {
                throw new HttpResponseException(
                    response()->json(['error' => 'O arquivo enviado não é válido.'], 400)
                );
            }

            $upload = $this->uploadService->handleUpload($file);

            return response()->json([
                'message' => 'Arquivo enviado com sucesso!',
                'upload' => $upload,
            ]);
        } catch (HttpResponseException $e) {
            return $e->getResponse();
        } catch (\Exception $e) {
            Log::error('Erro ao fazer upload do arquivo: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao processar o upload do arquivo.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

        /**
     * @OA\Get(
     *     path="/api/history",
     *     summary="Buscar histórico de upload de arquivos",
     *     description="Este endpoint retorna o histórico de arquivos enviados, podendo buscar por nome do arquivo ou data de referência.",
     *     operationId="getUploadHistory",
     *     tags={"Uploads"},
     *     @OA\Parameter(
     *         name="filename",
     *         in="query",
     *         description="Nome do arquivo para buscar no histórico.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Data de referência para buscar uploads.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de uploads retornada com sucesso.",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Upload"))
     *     )
     * )
     */
    public function history(Request $request)
    {
        try {
            $query = Upload::query();

            if ($request->has('filename')) {
                $query->where('file_name', 'like', '%' . $request->input('filename') . '%');
            }

            if ($request->has('date')) {
                $query->whereDate('uploaded_at', $request->input('date'));
            }

            $uploads = $query->get();

            return response()->json($uploads);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar histórico de uploads: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar histórico de uploads.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/search",
     *     summary="Buscar conteúdo do arquivo",
     *     description="Este endpoint permite buscar informações contidas em um arquivo enviado, filtrando por TckrSymb e RptDt.",
     *     operationId="searchContent",
     *     tags={"Uploads"},
     *     @OA\Parameter(
     *         name="TckrSymb",
     *         in="query",
     *         description="Símbolo do ticker para buscar.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="RptDt",
     *         in="query",
     *         description="Data do relatório para buscar.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Conteúdo do arquivo retornado com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="RptDt", type="string", example="2024-08-22"),
     *             @OA\Property(property="TckrSymb", type="string", example="AMZO34"),
     *             @OA\Property(property="MktNm", type="string", example="EQUITY-CASH"),
     *             @OA\Property(property="SctyCtgyNm", type="string", example="BDR"),
     *             @OA\Property(property="ISIN", type="string", example="BRAMZOBDR002"),
     *             @OA\Property(property="CrpnNm", type="string", example="AMAZON.COM, INC")
     *         )
     *     )
     * )
     */
    public function searchContent(Request $request)
    {

        $uploads = Upload::query();

        if ($request->has('TckrSymb')) {
            $uploads->where('TckrSymb', $request->input('TckrSymb'));
        }
        if ($request->has('RptDt')) {
            $uploads->where('RptDt', $request->input('RptDt'));
        }
    
        $result = $uploads->paginate(10);

        //dd($result->toArray());
    
        return response()->json($result);
    }
}
