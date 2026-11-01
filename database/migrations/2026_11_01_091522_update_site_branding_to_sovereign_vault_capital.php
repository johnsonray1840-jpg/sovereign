<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settingsToUpdate = [
            'name'           => 'sovereignvaultcapital.ltd',
            'site_name'      => 'sovereignvaultcapital.ltd',
            'logo_square'    => 'logo-square.png',
            'logo_rectangle' => 'logo-rectangle.png',
            'favicon'        => 'favicon.png',
        ];

        foreach ($settingsToUpdate as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        // Invalidate settings and layout cache so changes apply immediately
        try {
            Cache::forget('core_site_settings');
        } catch (\Throwable $e) {
            // Ignore cache store issues if any
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed
    }
};

