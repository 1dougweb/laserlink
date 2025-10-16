<!-- d2a12495-c572-4829-bbf7-3b09c5622af0 27c00eb5-f539-4739-871e-998c723e0209 -->
# Sistema de Páginas Simples

## Objetivo

Criar um CRUD simples de páginas customizadas (slug, título, conteúdo) com editor de texto rico e renderização de HTML.

## Implementação

### 1. Database - Migration e Model

**Arquivo:** `database/migrations/xxxx_create_pages_table.php`

- Campos: `id`, `title`, `slug` (unique), `content` (text), `is_active` (boolean), `meta_title`, `meta_description`, `timestamps`

**Arquivo:** `app/Models/Page.php`

- Fillable: title, slug, content, is_active, meta_title, meta_description
- Auto-geração de slug a partir do título
- Scope para páginas ativas

### 2. Controller Admin

**Arquivo:** `app/Http/Controllers/Admin/PageController.php`

- CRUD completo (index, create, store, edit, update, destroy)
- Validação de slug único
- Seguir padrão do PostController existente

### 3. Views Admin

**Diretório:** `resources/views/admin/pages/`

**index.blade.php:**

- Listagem de páginas com título, slug, status
- Botões de criar, editar, excluir
- Busca e filtros

**create.blade.php:**

- Formulário com: Título, Slug (auto-gerado), Conteúdo (Quill Editor)
- Toggle de status ativo/inativo
- Campos SEO opcionais (meta_title, meta_description)

**edit.blade.php:**

- Mesma estrutura do create, mas com dados carregados

### 4. Controller e View Pública

**Arquivo:** `app/Http/Controllers/PageController.php`

- Método `show($slug)` para exibir página pública
- Renderizar HTML com `{!! $page->content !!}` (interpretação de HTML)

**Arquivo:** `resources/views/pages/show.blade.php`

- Template simples para exibir a página
- Renderização do conteúdo HTML
- Meta tags SEO

### 5. Rotas

**Arquivo:** `routes/web.php`

- Admin: `/admin/pages` (resource routes)
- Pública: `/pagina/{slug}` ou `/{slug}` para exibir páginas

### 6. Menu Admin

- Adicionar link "Páginas" no menu lateral do admin (arquivo de layout)

## Arquivos Principais

- Migration: `database/migrations/xxxx_create_pages_table.php`
- Model: `app/Models/Page.php`
- Controllers: `app/Http/Controllers/Admin/PageController.php`, `app/Http/Controllers/PageController.php`
- Views: `resources/views/admin/pages/*`, `resources/views/pages/show.blade.php`
- Routes: `routes/web.php`

### To-dos

- [ ] Criar migration para tabela pages com campos necessários
- [ ] Criar Model Page com fillable, slug auto-generation e scopes
- [ ] Criar PageController no Admin com CRUD completo
- [ ] Criar views admin (index, create, edit) usando Quill Editor
- [ ] Criar PageController público com método show
- [ ] Criar view pública para renderizar HTML da página
- [ ] Adicionar rotas admin e públicas
- [ ] Adicionar link Páginas no menu admin