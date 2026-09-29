<?php

namespace App\Console\Commands;

use App\Models\SellerKycVerification;
use App\Services\ShiprocketService;
use Illuminate\Console\Command;

/**
 * Bulk-sync Shiprocket pickup locations for all verified sellers
 * whose pickup location has not yet been confirmed.
 *
 * Usage:
 *   php artisan shiprocket:sync-pickups              # dry-run (show what would be synced)
 *   php artisan shiprocket:sync-pickups --run         # actually sync
 *   php artisan shiprocket:sync-pickups --run --all   # re-sync ALL verified sellers (including already-synced)
 *   php artisan shiprocket:sync-pickups --list        # list all pickup locations in Shiprocket
 */
class ShiprocketSyncPickups extends Command
{
    protected $signature = 'shiprocket:sync-pickups
        {--run  : Actually perform the sync (default is dry-run)}
        {--all  : Re-sync even sellers that are already marked as synced}
        {--list : List all pickup locations currently registered in Shiprocket}';

    protected $description = 'Sync Shiprocket pickup locations for verified sellers';

    public function handle(ShiprocketService $shiprocket): int
    {
        // ── --list mode ──────────────────────────────────────────────────────
        if ($this->option('list')) {
            $this->info('Fetching pickup locations from Shiprocket...');
            $locations = $shiprocket->getPickupLocations();

            if (empty($locations)) {
                $this->warn('No pickup locations found (or API error — check laravel.log).');
                return 1;
            }

            $rows = array_map(function ($loc) {
                return [
                    $loc['id']              ?? '—',
                    $loc['pickup_location'] ?? ($loc['name'] ?? '—'),
                    $loc['city']            ?? '—',
                    $loc['state']           ?? '—',
                    $loc['pin_code']        ?? ($loc['pin'] ?? '—'),
                    $loc['status']          ?? '—',
                ];
            }, $locations);

            $this->table(
                ['ID', 'Location Name', 'City', 'State', 'Pincode', 'Status'],
                $rows
            );

            return 0;
        }

        // ── sync mode ─────────────────────────────────────────────────────────
        $dryRun = !$this->option('run');
        $reAll  = $this->option('all');

        if ($dryRun) {
            $this->comment('DRY-RUN mode — pass --run to actually sync.');
        }

        $query = SellerKycVerification::where('status', 'Verified')
            ->whereNotNull('shiprocket_pickup_location');

        if (!$reAll) {
            $query->where(function ($q) {
                $q->whereNull('pickup_sync_status')
                  ->orWhere('pickup_sync_status', '!=', 'synced');
            });
        }

        $kycs = $query->with('seller.user_info')->get();

        if ($kycs->isEmpty()) {
            $this->info('No sellers need pickup sync.');
            return 0;
        }

        $this->info("Found {$kycs->count()} seller(s) to process.");

        $success = 0;
        $failed  = 0;

        foreach ($kycs as $kyc) {
            $name   = $kyc->shiprocket_pickup_location;
            $seller = $kyc->seller;
            $label  = "Seller #{$kyc->user_id} — {$seller?->name} — '{$name}'";

            if ($dryRun) {
                $this->line("  [DRY-RUN] Would sync: {$label}");
                continue;
            }

            $this->line("  Syncing: {$label}");

            try {
                $synced = $shiprocket->syncSellerPickupLocation($kyc);

                if ($synced) {
                    $this->info("    ✓ Synced as '{$kyc->shiprocket_pickup_location}'");
                    $success++;
                } else {
                    $hint = $shiprocket->lastPickupError ?? 'check laravel.log for details';
                    $this->warn("    ✗ Sync failed — {$hint}");
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->error("    ✗ " . $e->getMessage());
                $failed++;
            }
        }

        if (!$dryRun) {
            $this->newLine();
            $this->info("Done. Succeeded: {$success} | Failed: {$failed}");
        } else {
            $this->newLine();
            $this->comment("Dry-run complete. Run with --run to apply.");
        }

        return $failed > 0 ? 1 : 0;
    }
}
