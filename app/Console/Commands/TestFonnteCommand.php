<?php

namespace App\Console\Commands;

use App\Services\FonnteService;
use Illuminate\Console\Command;

class TestFonnteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fonnte:test {target : The destination phone number (e.g. 08123456789)} {--message= : Custom test message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending a WhatsApp message via Fonnte API';

    /**
     * Execute the console command.
     */
    public function handle(FonnteService $fonnteService): int
    {
        $target = $this->argument('target');
        $message = $this->option('message') ?? "Halo! Ini adalah pesan uji coba (test notification) dari sistem PPDB SMK Mandiri Pontianak via Fonnte WhatsApp API.";

        $this->info("Mengirim pesan WhatsApp ke: {$target}...");
        
        $result = $fonnteService->sendMessage($target, $message);

        if ($result) {
            $this->info("✅ Pesan WhatsApp BERHASIL dikirim!");
            return Command::SUCCESS;
        }

        $this->error("❌ Gagal mengirim pesan WhatsApp. Silakan periksa log di storage/logs/laravel.log untuk detailnya.");
        return Command::FAILURE;
    }
}
