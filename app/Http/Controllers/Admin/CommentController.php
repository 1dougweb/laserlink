<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * Listar todos os comentários
     */
    public function index(Request $request): View
    {
        $query = Comment::with(['post', 'user'])->latest();

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por busca
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('content', 'like', "%{$request->search}%")
                  ->orWhere('author_name', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function ($userQuery) use ($request) {
                      $userQuery->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        $comments = $query->paginate(20);
        $pendingCount = Comment::pending()->count();

        return view('admin.comments.index', compact('comments', 'pendingCount'));
    }

    /**
     * Aprovar comentário
     */
    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Comentário aprovado com sucesso!');
    }

    /**
     * Rejeitar comentário
     */
    public function reject(Comment $comment): RedirectResponse
    {
        $comment->update([
            'status' => 'rejected',
        ]);

        return redirect()->back()
            ->with('success', 'Comentário rejeitado.');
    }

    /**
     * Excluir comentário
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->back()
            ->with('success', 'Comentário excluído com sucesso!');
    }

    /**
     * Obter comentários pendentes (para notificações)
     */
    public function pending(): JsonResponse
    {
        $comments = Comment::with(['post', 'user'])
            ->pending()
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'post_title' => $comment->post->title,
                    'author' => $comment->author_name,
                    'content' => \Str::limit($comment->content, 60),
                    'created_at' => $comment->created_at->diffForHumans(),
                    'url' => route('admin.comments.index', ['status' => 'pending']),
                ];
            });

        return response()->json([
            'success' => true,
            'count' => Comment::pending()->count(),
            'comments' => $comments,
        ]);
    }
}
