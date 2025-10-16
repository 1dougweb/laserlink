<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    /**
     * Upload de imagem para o TinyMCE
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|image|max:2048', // 2MB max
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('editor-images', 'public');
                
                return response()->json([
                    'location' => url('images/' . $path),
                ]);
            }

            return response()->json([
                'error' => 'Nenhum arquivo foi enviado.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao fazer upload da imagem: ' . $e->getMessage(),
            ], 500);
        }
    }
}
