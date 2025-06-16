<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
// Ensure you have the correct path if House model is elsewhere, e.g., App\Models\House
// If House model is directly in App/, it would be use App\House;
use App\Models\House; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CompleteExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:complete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for expired bookings, marks them as completed, and updates house availability.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to process expired bookings...');
        Log::channel('scheduler')->info('Scheduler: Starting CompleteExpiredBookings command.'); // Using a specific log channel

        $countCompleted = 0;
        $countMadeAvailable = 0;

        // Get bookings that are 'accepted' or 'agreement_signed'
        // Eager load house to prevent N+1 queries
        $activeBookings = Booking::with('house') 
                                ->whereIn('status', ['accepted', 'agreement_signed'])
                                ->get();

        if ($activeBookings->isEmpty()) {
            $this->info('No active bookings found to process.');
            Log::channel('scheduler')->info('Scheduler: No active bookings found.');
            return 0; 
        }

        foreach ($activeBookings as $booking) {
            if (!$booking->created_at || !$booking->month_duration) {
                $warningMsg = "Booking ID {$booking->id} is missing created_at or month_duration, skipping.";
                $this->warn($warningMsg);
                Log::channel('scheduler')->warning($warningMsg);
                continue;
            }

            // Calculate end date based on created_at and month_duration
            $endDate = $booking->created_at->copy()->addMonths($booking->month_duration);

            if ($endDate->isPast()) {
                // Booking has expired
                $booking->status = 'completed';
                
                $house = $booking->house; // Get the related house

                if ($house) {
                    // Only change house status if it's currently 'booked'
                    // This prevents overriding a status like 'maintenance' or 'disagree' if set manually for other reasons
                    if ($house->status === 'booked') {
                        $house->status = 'available';
                        if ($booking->save() && $house->save()) { // Save both and check success
                            $countCompleted++;
                            $countMadeAvailable++;
                            $logMsg = "Booking ID {$booking->id} marked as completed. House ID {$house->id} marked as available.";
                            $this->info($logMsg);
                            Log::channel('scheduler')->info($logMsg);
                        } else {
                            $errorMsg = "Failed to save Booking ID {$booking->id} or House ID {$house->id}.";
                            $this->error($errorMsg);
                            Log::channel('scheduler')->error($errorMsg);
                        }
                    } else {
                        // If house is not 'booked', still mark booking as completed but log house status
                        $booking->save();
                        $countCompleted++;
                        $logMsg = "Booking ID {$booking->id} marked as completed. House ID {$house->id} status ('{$house->status}') was not 'booked', so it was not changed to 'available'.";
                        $this->info($logMsg);
                        Log::channel('scheduler')->info($logMsg);
                    }
                } else {
                    // Booking has no associated house, just mark booking as completed
                    $booking->save();
                    $countCompleted++;
                    $warningMsg = "Booking ID {$booking->id} marked as completed, but it has no associated house. House status not changed.";
                    $this->warn($warningMsg);
                    Log::channel('scheduler')->warning($warningMsg);
                }
            }
        }

        $summaryMsg = "Processing complete. {$countCompleted} bookings processed. {$countMadeAvailable} houses newly marked as available.";
        $this->info($summaryMsg);
        Log::channel('scheduler')->info($summaryMsg);

        $this->info('Checking for already completed bookings with houses still marked as booked...');
        Log::channel('scheduler')->info('Scheduler: Checking for already completed bookings with houses still marked as booked...');
        $alreadyCompletedBookings = Booking::with('house')
                                      ->where('status', 'completed')
                                      ->whereHas('house', function ($query) {
                                          $query->where('status', 'booked');
                                      })
                                      ->get();
        $countAlreadyCompletedFixed = 0;

        if ($alreadyCompletedBookings->isEmpty()) {
            $this->info('No already completed bookings found with houses still marked as booked.');
            Log::channel('scheduler')->info('Scheduler: No already completed bookings found with houses still marked as booked.');
        } else {
            foreach ($alreadyCompletedBookings as $booking) {
                if (!$booking->created_at || !$booking->month_duration) {
                    $warningMsg = "Booking ID {$booking->id} (already completed) is missing created_at or month_duration, skipping house status update.";
                    $this->warn($warningMsg);
                    Log::channel('scheduler')->warning($warningMsg);
                    continue;
                }

                $endDate = $booking->created_at->copy()->addMonths($booking->month_duration);

                if ($endDate->isPast() && $booking->house && $booking->house->status === 'booked') {
                    $booking->house->status = 'available';
                    if ($booking->house->save()) {
                        $countAlreadyCompletedFixed++;
                        $logMsg = "House ID {$booking->house->id} (for already completed Booking ID {$booking->id}) marked as available.";
                        $this->info($logMsg);
                        Log::channel('scheduler')->info($logMsg);
                    } else {
                        $errorMsg = "Failed to save House ID {$booking->house->id} (for already completed Booking ID {$booking->id}).";
                        $this->error($errorMsg);
                        Log::channel('scheduler')->error($errorMsg);
                    }
                }
            }
        }

        if ($countAlreadyCompletedFixed > 0) {
            $additionalSummary = "Additionally, {$countAlreadyCompletedFixed} houses for already completed and past bookings were marked as available.";
            $this->info($additionalSummary);
            Log::channel('scheduler')->info($additionalSummary);
        }
        
        // For Laravel 9+ use Command::SUCCESS, for older versions return 0.
        // Assuming a modern Laravel version, but 0 is generally safe.
        if (defined('Illuminate\Console\Command::SUCCESS')) {
            return Command::SUCCESS;
        }
        return 0;
    }
}
