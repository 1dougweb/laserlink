<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    public function page()
    {
        return view('admin.file-manager-standalone');
    }

    public function index(Request $request)
    {
        try {
            $directory = $request->get('directory', '');
            
            // Listar diretórios
            $directories = Storage::disk('public')->directories($directory);
            
            $dirs = collect($directories)->map(function ($dir) {
                return [
                    'name' => basename($dir),
                    'path' => $dir,
                    'type' => 'directory',
                    'url' => null,
                    'size' => null,
                    'modified' => Storage::disk('public')->lastModified($dir),
                    'extension' => null,
                ];
            });

            // Listar arquivos
            $files = Storage::disk('public')->files($directory);
            
            $files = collect($files)->map(function ($file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                // O disco 'public' está configurado para public_path('images')
                // então a URL deve ser /images/{path}
                $url = url('images/' . $file);
                
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'type' => 'file',
                    'url' => $url,
                    'size' => Storage::disk('public')->size($file),
                    'modified' => Storage::disk('public')->lastModified($file),
                    'extension' => $extension ?: null,
                ];
            });

            // Combinar e ordenar
            $items = $dirs->concat($files)->sortBy('name')->values();

            // Gerar breadcrumb
            $breadcrumb = [];
            if ($directory) {
                $parts = explode('/', $directory);
                $path = '';
                foreach ($parts as $part) {
                    $path .= ($path ? '/' : '') . $part;
                    $breadcrumb[] = [
                        'name' => $part,
                        'path' => $path,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'items' => $items,
                'current_directory' => $directory,
                'parent_directory' => $directory ? dirname($directory) : null,
                'breadcrumb' => $breadcrumb,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro no FileManagerController::index', [
                'message' => $e->getMessage(),
                'directory' => $request->get('directory', ''),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar arquivos: ' . $e->getMessage(),
                'items' => [],
                'breadcrumb' => []
            ], 500);
        }
    }

    public function createDirectory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'directory' => 'nullable|string',
        ]);

        $parentDir = $request->get('directory', '');
        $newDir = $parentDir ? $parentDir . '/' . $request->name : $request->name;

        if (Storage::disk('public')->exists($newDir)) {
            return response()->json(['success' => false, 'message' => 'Diretório já existe'], 400);
        }

        Storage::disk('public')->makeDirectory($newDir);

        return response()->json(['success' => true, 'path' => $newDir]);
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240', // 10MB
                'directory' => 'nullable|string',
            ]);

            $directory = $request->get('directory', '');
            $file = $request->file('file');
            
            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum arquivo foi enviado'
                ], 400);
            }

            // Log para debug
            \Log::info('Upload iniciado', [
                'directory' => $directory,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
            ]);

            $path = $file->store($directory, 'public');

            if (!$path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Falha ao salvar o arquivo'
                ], 500);
            }

            \Log::info('Upload concluído', ['path' => $path]);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => url('images/' . $path),
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $e->errors()['file'] ?? ['Erro desconhecido'])
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erro no upload', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer upload: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'type' => 'required|in:file,directory',
        ]);

        $path = $request->path;
        
        if ($request->type === 'directory') {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->deleteDirectory($path);
                return response()->json(['success' => true]);
            }
        } else {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Item não encontrado'], 404);
    }

    public function rename(Request $request)
    {
        $request->validate([
            'old_path' => 'required|string',
            'new_name' => 'required|string|max:255',
        ]);

        $oldPath = $request->old_path;
        $newPath = dirname($request->old_path) . '/' . $request->new_name;

        if (Storage::disk('public')->exists($newPath)) {
            return response()->json(['success' => false, 'message' => 'Já existe um item com este nome'], 400);
        }

        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->move($oldPath, $newPath);
            return response()->json(['success' => true, 'new_path' => $newPath]);
        }

        return response()->json(['success' => false, 'message' => 'Item não encontrado'], 404);
    }

    public function move(Request $request)
    {
        $request->validate([
            'source' => 'required|string',
            'destination' => 'nullable|string',
        ]);

        $source = $request->input('source');
        $destination = $request->input('destination');

        \Illuminate\Support\Facades\Log::info('Move request:', ['source' => $source, 'destination' => $destination]);

        try {
            // Verificar se o arquivo/pasta de origem existe
            if (!Storage::disk('public')->exists($source)) {
                return response()->json(['success' => false, 'message' => 'Arquivo/pasta não encontrado'], 404);
            }

            $fileName = basename($source);
            $newPath = $destination ? $destination . '/' . $fileName : $fileName;

            // Verificar se já existe um arquivo/pasta com o mesmo nome no destino
            if (Storage::disk('public')->exists($newPath)) {
                return response()->json(['success' => false, 'message' => 'Já existe um item com este nome no destino'], 400);
            }

            // Mover o arquivo/pasta
            Storage::disk('public')->move($source, $newPath);

            return response()->json(['success' => true, 'message' => 'Item movido com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao mover item: ' . $e->getMessage()], 500);
        }
    }
}
