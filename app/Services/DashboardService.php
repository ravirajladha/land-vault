<?php
// File: app/Services/DashboardtService.php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\DocumentStatusLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\{Receiver, Master_doc_data,User,Advocate,Advocate_documents};

class DashboardService
{
    public function getDocumentCounts()
    {
        return Master_doc_data::count();
    }
    public function getRecieverCount()
    {
        return Receiver::where('status', 1)->count();
    }

    public function getDocumentCountsByType()
    {
        $docTypes = DB::table('master_doc_types')->orderBy('name')->pluck('name');
        $chartLabels = []; //for email pie chart
        $chartCounts = []; //for email pie chart
        $acceptedCounts = []; //to get the accepted doc
        $notAcceptedCounts = []; // to get the pending doc
        $holdedCounts = []; // to get the pending doc
        $reviewerHoldCounts = []; // to get the pending doc
        $userCounts = 0;
       $userCounts =  User::where('type', "user")->count();
        foreach ($docTypes as $docType) {
            $existsInMasterDocData = Master_doc_data::where('document_type_name', $docType)->exists();
            // if ($existsInMasterDocData && Schema::hasTable($docType)) {
            if (Schema::hasTable($docType)) {
                $count = DB::table($docType)->count() ?   DB::table($docType)->count(): 0;
// dd($count);
                $formattedLabel = ucwords(str_replace('_', ' ', $docType));
                
                $chartLabels[] = $formattedLabel;
                $chartCounts[] = $count;


                // not Accepted count
                $notAcceptedCount  = DB::table($docType)->where('status', 0)->count();
                $notAcceptedCounts[] = $notAcceptedCount;

                // Accepted count
                $acceptedCount = DB::table($docType)->where('status', 1)->count();
                $acceptedCounts[] = $acceptedCount;
                // Hold  count
                $holdedCount = DB::table($docType)->where('status', 2)->count();
                $holdedCounts[] = $holdedCount;
                // reviewerHoldCounts feedback  count
                $reviewerHoldCount = DB::table($docType)->where('status', 3)->count();
                $reviewerHoldCounts[] = $reviewerHoldCount;
            } else {
                $chartCounts[] = 0;
                $acceptedCounts[] = 0;
                $notAcceptedCounts[] = 0;
                $holdedCounts[] = 0;
                $reviewerHoldCounts[] = 0;
            }
        }
        // dd($chartLabels);
        // dd(array_sum($notAcceptedCounts));
        $total_document_type = count($chartLabels);
// dd($total_document_type);
        $colors = $this->generateColorPalette(count($chartLabels));
        return compact('chartLabels', 'chartCounts', 'colors', 'acceptedCounts', 'notAcceptedCounts', 'holdedCounts', 'total_document_type','userCounts','reviewerHoldCounts');
    }
    private function generateColorPalette($numColors)
    {
        $colors = [];

        if ($numColors > 0) {
            $hueStep = 360 / $numColors;

            for ($i = 0; $i < $numColors; $i++) {
                $hue = $i * $hueStep;
                $colors[] = "hsl(" . $hue . ", 70%, 60%)"; // Adjust saturation and lightness as needed
            }
        } else {
            // Return a default color or empty array if there are no colors to generate
            $colors = ['#cccccc']; // A default color, could be any color of your choice
            // OR simply return an empty array if no default color is desired
            // $colors = [];
        }

        return $colors;
    }

    public function getGeographicalCounts()
    {
        // $villageCount = Master_doc_data::distinct('village')->count('village');
        $villages = Master_doc_data::pluck('village')->toArray(); // Fetch all village data
        $allVillages = [];

        foreach ($villages as $villageString) {
            if (!empty(trim($villageString))) {
                $splitVillages = explode(',', $villageString); // Split village names
                $splitVillages = array_filter($splitVillages, function ($village) {
                    return !empty(trim($village)); // Filter out empty strings
                });
                $allVillages = array_merge($allVillages, $splitVillages);
            }
        }

        $distinctVillages = array_unique($allVillages); // Remove duplicates
        $villageCount = count($distinctVillages); // Count distinct villages

        $villageCount = count($distinctVillages); // Count distinct villages

        $talukCount = Master_doc_data::distinct('taluk')->count('taluk');
        $districtCount = Master_doc_data::distinct('district')->count('district');
        // Sum of area for acres and cents
        $totalAreaAcre = Master_doc_data::where('unit', 1)->sum('area');

        // Sum of area for square feer
        $totalAreaFeet = Master_doc_data::where('unit', 2)->sum('area');

        return [
            'villageCount' => $villageCount,
            'talukCount' => $talukCount,
            'districtCount' => $districtCount,
            'totalAreaAcre' => $totalAreaAcre,
            'totalAreaFeet' => $totalAreaFeet
        ];
    }

    public function getCategoryDocumentCounts()
    {
        // Fetch the categories along with the count of how many times each category_id is used in master_doc_datas
        $categoryCounts = DB::table('master_doc_datas')
            ->join('categories', 'master_doc_datas.category_id', '=', 'categories.id') // Join the two tables
            ->select('categories.name', DB::raw('count(master_doc_datas.category_id) as document_count')) // Select the category name and count
            ->groupBy('categories.name') // Group by category name to get the count per category
            ->orderBy('categories.name', 'asc') // Sort categories alphabetically (optional)
            ->get();
        
        // Convert the results to a simple array if needed
        $categoryCountsArray = [];
        foreach ($categoryCounts as $category) {
            $categoryCountsArray[$category->name] = $category->document_count;
        }
// dd($categoryCountsArray);
        return $categoryCountsArray;
    }

    
    // public function getCategoryDocumentCounts()
    // {
    //     // Fetch all categories as a single list
    //     $allCategories = Master_doc_data::pluck('category');
    
    //     // Initialize an empty array to hold the count of each category
    //     $categoryCounts = [];
    
    //     // Loop through each document's categories
    //     foreach ($allCategories as $categories) {
    //         // Split categories by comma and trim spaces
    //         foreach (explode(',', $categories) as $category) {
    //             $category = trim($category);
    //             // Increment the count for each category
    //             if (!empty($category)) {
    //                 if (!isset($categoryCounts[$category])) {
    //                     $categoryCounts[$category] = 0;
    //                 }
    //                 $categoryCounts[$category]++;
    //             }
    //         }
    //     }
    
    //     // Sort categories alphabetically before returning (optional)
    //     ksort($categoryCounts);
    
    //     return $categoryCounts;
    // }
    
    public function getUsersWithTodayCounts()
    {
        // Get today's date
        $today = Carbon::now()->toDateString();

        // Get all users
        $users = User::orderBy('id', 'desc')->get();

        // Initialize an array to store users with today's counts
        $usersWithCounts = [];

        // Loop through each user
        foreach ($users as $user) {
            // Get the today's count for each status type for the current user
            $todayCounts = DocumentStatusLog::where('created_by', $user->id)
                ->whereDate('created_at', $today)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            // Calculate total count for today
            $totalTodayCount = array_sum($todayCounts);

            // Add the today's counts to the user's data
            $user->todayCounts = [
                'Pending' => $todayCounts[0] ?? 0,
                'Approved' => $todayCounts[1] ?? 0,
                'Hold' => $todayCounts[2] ?? 0,
                'Reviewer Feedback' => $todayCounts[3] ?? 0,
                'Total' => $totalTodayCount,
            ];

            // Add the user to the array
            $usersWithCounts[] = $user;
        }

   //     dd($usersWithCounts);
        return $usersWithCounts;
    }
    public function getAdvocateDocumentCounts()
    {
        // Fetch all advocates with their documents
        $advocatesWithDocuments = Advocate_documents::with('advocate')->get();

        // Process the data if needed (e.g., count documents per advocate)
        $advocateDocumentCounts = $advocatesWithDocuments->groupBy('advocate_id')->map(function ($documents) {
            return [
                'advocate_id' => $documents->first()->advocate_id,
                'document_count' => $documents->count(),
                'advocate_name' => $documents->first()->advocate->name, // Assuming there is a name field in the advocate model
            ];
        });

        return $advocateDocumentCounts;
    }
}
