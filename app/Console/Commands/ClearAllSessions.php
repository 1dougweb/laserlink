<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearAllSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpar todas as sessões do banco de dados (útil para resolver erro 419)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🗑️  Limpando todas as sessões...');
        
        try {
            $count = DB::table('sessions')->count();
            DB::table('sessions')->truncate();
            
            $this->info("✅ {$count} sessão(ões) removida(s) com sucesso!");
            $this->warn('⚠️  Todos os usuários logados serão desconectados.');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Erro ao limpar sessões: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

