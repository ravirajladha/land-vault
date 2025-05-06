<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Compliance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */



     protected function schedule(Schedule $schedule): void
{
    // Command to delete old logs daily
    $schedule->command('logs:delete-old')->daily();

    // Notify users about upcoming compliances due in the next 30 days
    $schedule->call(function () {
        $notificationService = resolve('App\Services\NotificationService');
        $systemUserId = 1; 
        $today = Carbon::today();

        // Retrieve compliances due in the next 30 days
        $upcomingCompliances = Compliance::whereBetween('due_date', [$today->copy()->subDays(30), $today])
                                          ->where('status', 0) // Assuming 'status' field exists
                                          ->get();

        foreach ($upcomingCompliances as $compliance) {
            $notificationService->createComplianceNotification('upcoming', $compliance, $systemUserId);
        }
    })->daily();
    
    // Create new compliances for recurring ones that are settled
    $schedule->call(function () {
        $today = Carbon::today();

        // Select compliances with status 1 (settled) and is recurring
        $compliances = Compliance::where('due_date', '<=', $today)
                                 ->where('is_recurring', 1)
                                 ->where('status', 1)
                                 ->get();

        foreach ($compliances as $compliance) {
            // Calculate the next due date based on recurrence interval
            switch ($compliance->recurrence_interval) {
                case '1_months':
                    $newDueDate = Carbon::parse($compliance->due_date)->addMonth();
                    break;
                case '3_months':
                    $newDueDate = Carbon::parse($compliance->due_date)->addMonths(3);
                    break;
                case '6_months':
                    $newDueDate = Carbon::parse($compliance->due_date)->addMonths(6);
                    break;
                case '12_months':
                    $newDueDate = Carbon::parse($compliance->due_date)->addYear();
                    break;
                default:
                    $newDueDate = null;
                    break;
            }

            // Create a new compliance if the new due date is calculated
            if ($newDueDate) {
                $newCompliance = $compliance->replicate(['id']); // Exclude id when replicating
                $newCompliance->due_date = $newDueDate;
                $newCompliance->status = 0; // Set to pending or your default status
                $newCompliance->save();

                // Update the current compliance to indicate it has been processed for recurrence
                $compliance->status = 3; // Mark this as processed, assuming 3 means completed or not to be processed again
                $compliance->save();
            }
        }
    })->daily();
}


    // protected function schedule(Schedule $schedule): void
    // {
    //     // Command to delete old logs daily
    //     $schedule->command('logs:delete-old')->daily();

    //     // Notify users about upcoming compliances due in the next 30 days
    //     $schedule->call(function () {
    //         $notificationService = resolve('App\Services\NotificationService');
    //         $systemUserId = 1; 
    //         $today = Carbon::today();

    //         // Retrieve compliances due in the next 30 days
    //         $upcomingCompliances = Compliance::whereBetween('due_date', [$today->copy()->subDays(30), $today])
    //                                           ->where('status', 0) // Assuming 'status' field exists
    //                                           ->get();

    //         foreach ($upcomingCompliances as $compliance) {
    //             $notificationService->createComplianceNotification('upcoming', $compliance, $systemUserId);
    //         }
    //     })->daily();
        
    //     // Create new compliances for recurring ones that are settled
    //     $schedule->call(function () {
    //         $today = Carbon::today();

    //         // Select compliances with status 1 (settled) and is recurring
    //         $compliances = Compliance::where('due_date', '<=', $today)
    //                                  ->where('is_recurring', 1)
    //                                  ->where('status', 1)
    //                                  ->get();

    //         foreach ($compliances as $compliance) {
    //             // Calculate the next due date based on recurrence months
    //             $recurrenceMonths = $compliance->recurrence_months ?? 12; // Default to 12 months if not specified
    //             $newDueDate = $compliance->due_date->addMonths($recurrenceMonths);

    //             // Create a new compliance for the next period
    //             $newCompliance = $compliance->replicate(['id']); // Exclude id when replicating
    //             $newCompliance->due_date = $newDueDate;
    //             $newCompliance->status = 0; // Set to pending or your default status
    //             $newCompliance->save();

    //             // Update the current compliance to indicate it's been processed for recurrence
    //             $compliance->status = 3; // Assuming 3 indicates processed status, which should not be repeated in the future
    //             $compliance->save();
    //         }
    //     })->daily();
    // }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
