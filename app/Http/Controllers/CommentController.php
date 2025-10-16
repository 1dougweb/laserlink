<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Armazenar um novo comentário
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
            'author_name' => auth()->check() ? 'nullable' : 'required|string|max:100',
            'author_email' => auth()->check() ? 'nullable' : 'required|email|max:100',
        ], [
            'content.required' => 'O comentário não pode estar vazio.',
            'content.min' => 'O comentário deve ter no mínimo 3 caracteres.',
            'content.max' => 'O comentário não pode ter mais de 1000 caracteres.',
            'author_name.required' => 'Por favor, informe seu nome.',
            'author_email.required' => 'Por favor, informe seu e-mail.',
            'author_email.email' => 'Por favor, informe um e-mail válido.',
        ]);

        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->content = $validated['content'];
        $comment->ip_address = $request->ip();
        $comment->user_agent = $request->userAgent();

        if (auth()->check()) {
            $comment->user_id = auth()->id();
        } else {
            $comment->author_name = $validated['author_name'];
            $comment->author_email = $validated['author_email'];
        }

        $comment->save();

        return redirect()->back()
            ->with('success', 'Comentário enviado com sucesso! Ele será publicado após aprovação.');
    }
}
