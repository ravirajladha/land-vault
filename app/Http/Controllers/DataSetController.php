<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{Receiver, Receiver_type, Master_doc_type,Category,Subcategory};

class DataSetController extends Controller
{
    public function configure()
    {
        $receiver_type_count = Receiver_type::count();
        $categories_count = Category::count();
        $subcategories_count = Subcategory::count();
        // dd($receiver_type_count);
        $data = [
            'receiver_type_count' => $receiver_type_count,
            'categories_count' => $categories_count,
            'subcategories_count' => $subcategories_count,
        ];
        // dd($categories_count);
        return view('pages.data-sets.data-sets', $data);
    }

    //receiver types function
    public function receiverType()
    {
        $data = Receiver_type::get();
        return view('pages.data-sets.receiver-type', ['data' => $data]);
    }

    public function addReceiverType(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:receiver_types', // Adjust the table name as needed
        ]);

        // Create a new receiver type
        $receiverType = new Receiver_type;
        $receiverType->name = $request->name;
        // Assign other fields as necessary
        $receiverType->created_by =  Auth::user()->id;
        // Save the receiver type to the database
        $receiverType->save();

        return response()->json(['success' => 'Receiver type added successfully.']);
    }

    public function updateReceiverType(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:receiver_types,id',
            'name' => 'required|string|max:255', // Validation rules as per your requirements
        ]);

        try {
            $receiverType = Receiver_type::findOrFail($request->id);
            $receiverType->name = $request->name;
            // Update other fields as necessary

            $receiverType->save();

            return response()->json(['success' => 'Receiver type updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the receiver type.'], 500);
        }
    }
   
 //category
 public function showCategories()
 {
     $categories = Category::all();
     return view('pages.data-sets.categories', ['data' => $categories]);
 }

 /**
  * Add a new category.
  */
 public function addCategory(Request $request)
 {
    // dd($request->all());
    Log::info("Adding category",$request->all() );
     // Validate the request data
     $request->validate([
         'name' => 'required|string|max:255|unique:categories',
     ]);

   

     // Create a new category
     $category = new Category;
     $category->name = $request->name;
 
     $category->status = 1; // Default status to active
    //  $category->created_by = Auth::user()->id;

     // Save the category to the database
     $category->save();

     return response()->json(['success' => 'Category added successfully.']);
 }

 /**
  * Update an existing category.
  */
  public function updateCategory(Request $request)
  {
      Log::info("Update category", $request->all());
  
      // Validate the request data
      $request->validate([
          'id' => 'required|exists:categories,id',
          'name' => [
              'required',
              'string',
              'max:255',
              // Add unique validation rule excluding the current category ID
              Rule::unique('categories')->ignore($request->id),
          ],
      ]);
  
      try {
          // Find the category by ID
          $category = Category::findOrFail($request->id);
          $category->name = $request->name;
  
          // Update other fields as necessary
  
          // Save the updated category
          $category->save();
  
          return response()->json(['success' => 'Category updated successfully.']);
      } catch (\Exception $e) {
        Log::error("Error updating category: " . $e->getMessage());
        return response()->json(['error' => 'An error occurred while updating the category.'], 500);
      }
  }

    


      // Show all subcategories
      public function showSubcategories()
      {
     $categories = Category::all();

          $subcategories = Subcategory::with('category')->get();
          return view('pages.data-sets.subcategories', ['data' => $subcategories,'categories'=> $categories]);
      }
  
      // Add a new subcategory
      public function addSubcategory(Request $request)
      {
          Log::info("Adding subcategory", $request->all());
  
          // Validate the request data
          $request->validate([
              'name' => 'required|string|max:255|unique:subcategories',
              'category_id' => 'required|exists:categories,id',
          ]);
  
          // Create a new subcategory
          $subcategory = new Subcategory;
          $subcategory->name = $request->name;
          $subcategory->category_id = $request->category_id;
          $subcategory->status = 1; // Default status to active
  
          // Save the subcategory to the database
          $subcategory->save();
  
          return response()->json(['success' => 'Subcategory added successfully.']);
      }
  
      // Update an existing subcategory
      public function updateSubcategory(Request $request)
      {
          Log::info("Update subcategory", $request->all());
  
          // Validate the request data
          $request->validate([
              'id' => 'required|exists:subcategories,id',
              'name' => [
                  'required',
                  'string',
                  'max:255',
                  Rule::unique('subcategories')->ignore($request->id),
              ],
          ]);
  
          try {
              // Find the subcategory by ID
              $subcategory = Subcategory::findOrFail($request->id);
              $subcategory->name = $request->name;
  
              // Update other fields as necessary
  
              // Save the updated subcategory
              $subcategory->save();
  
              return response()->json(['success' => 'Subcategory updated successfully.']);
          } catch (\Exception $e) {
              Log::error("Error updating subcategory: " . $e->getMessage());
              return response()->json(['error' => 'An error occurred while updating the subcategory.'], 500);
          }
      }
    }