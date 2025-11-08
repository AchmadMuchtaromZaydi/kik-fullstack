<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Organisasi;
use App\Models\DataPendukung;
use Illuminate\Support\Facades\Storage;

class FixOrganisasiStorage extends Command
{
    protected $signature = 'storage:fix-organisasi {organisasi_id?}';
    protected $description = 'Fix storage paths for organisasi documents';

    public function handle()
    {
        $organisasiId = $this->argument('organisasi_id');

        if ($organisasiId) {
            $organisasi = Organisasi::find($organisasiId);
            if ($organisasi) {
                $this->fixOrganisasi($organisasi);
            }
        } else {
            $organisations = Organisasi::has('dataPendukung')->get();
            foreach ($organisations as $organisasi) {
                $this->fixOrganisasi($organisasi);
            }
        }

        $this->info('Storage fix completed!');
    }

    private function fixOrganisasi($organisasi)
    {
        $this->info("Checking organisasi: {$organisasi->nama} (ID: {$organisasi->id})");

        foreach ($organisasi->dataPendukung as $doc) {
            $this->checkAndFixFile($organisasi, $doc);
        }
    }

    private function checkAndFixFile($organisasi, $doc)
    {
        $currentPath = $doc->image;

        if (!$currentPath) {
            $this->warn("No file path for document: {$doc->tipe}");
            return;
        }

        // Check if file exists in current path
        if (Storage::exists($currentPath)) {
            $this->info("✓ File exists: {$currentPath}");
            return;
        }

        // Try to find file in alternative locations
        $possiblePaths = [
            'uploads/organisasi/' . $organisasi->id . '/' . $currentPath,
            'organisasi/' . $organisasi->id . '/' . $currentPath,
            'public/uploads/organisasi/' . $organisasi->id . '/' . $currentPath,
            $currentPath,
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::exists($path)) {
                $this->info("✓ File found at: {$path}");
                // Update the path in database if needed
                // $doc->update(['image' => $path]);
                return;
            }
        }

        $this->error("✗ File not found: {$currentPath}");
    }
}
