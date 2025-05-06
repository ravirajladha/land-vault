<?php

namespace App\Services;

use App\Models\Master_doc_data;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DocumentService
{
    public function saveDocumentData($data, $doc_id = null)
    {

        $nameUniqueRule = Rule::unique('master_doc_datas', 'name');
        if ($doc_id) {
            // Exclude the current document from the unique check
            $nameUniqueRule->ignore($doc_id);
        }
        $validator = Validator::make($data, [
            'name' => [
                'required',
                'string',
                // $nameUniqueRule, 
            ],
            'location' => 'nullable|string',
            'doc_identifier_id' => 'nullable|string',
            'locker_id' => 'nullable|numeric',
            'number_of_page' => 'nullable|integer',
            'document_type' => 'nullable|integer',
            'type' => 'string|required',
            'current_state' => 'nullable|string',
            'state' => 'nullable|string',
            'alternate_state' => 'nullable|string',
            'current_district' => 'nullable|string',
            'district' => 'nullable|string',
            'alternate_district' => 'nullable|string',
            'current_taluk' => 'nullable|string',
            'taluk' => 'nullable|string',
            'alternate_taluk' => 'nullable|string',
            'current_village' => 'nullable|string',
            'village' => 'nullable|string',
            'alternate_village' => 'nullable|string',
            'issued_date' => 'nullable|date',
            'current_town' => 'nullable|string',
            'town' => 'nullable|string',
            'alternate_town' => 'nullable|string',
            'old_locker_number' => 'nullable|integer',
            'physically' => 'nullable|string',
            // 'category' => 'nullable|string',
            // 'court_case_no' => 'nullable|string',
            'doc_no' => 'nullable|string',
            'survey_no' => 'nullable|string',
            'wet_land' => 'nullable|string',
            'dry_land' => 'nullable|string',
            'unit' => 'nullable|string',
            'area' => 'nullable|string',
            'categories.*' => 'exists:categories,id',
            'subcategories.*' => 'exists:subcategories,id',
            'latitude' => ['nullable', 'string', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['nullable', 'string', 'regex:/^[-]?(([1]?[0-7]?[0-9])\.(\d+))|(180(\.0+)?)$/'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => 'fail',
                'errors' => $validator->errors()->all()
            ];
        }
        if(isset($data['categories'])){

            $categories = implode(',', $data['categories']);
        }else{
            $categories= null;
        }
        if(isset($data['subcategories'])){

            $subcategories = implode(',', $data['subcategories']);
        }else{
            $subcategories  = null;
        }
      
        if ($doc_id) {
            $masterDocData = Master_doc_data::findOrFail($doc_id);
        } else {
            $existingDocument = Master_doc_data::where('name', $data['name'])->first();
            if ($existingDocument) {
                return [
                    'status' => 'fail',
                    'errors' => 'Document with this name already exists.'
                ];
            }
            $masterDocData = new Master_doc_data;
        }
        [$id, $tableName] = explode('|', $data['type'], 2);
        $selectedSets = isset($data['set']) ? $data['set'] : [];
        $setsAsString = json_encode($selectedSets);
        // dd($data);
        $masterDocData->fill([
            'name' => $data['name'],
            'location' => $data['location'],
            'doc_identifier_id' => $data['doc_identifier_id'],
            'locker_id' => $data['locker_id'],
            'number_of_page' => $data['number_of_page'],
            'document_type' => $id,
            'document_type_name' => $tableName,
            // 'current_state' => $data['current_state'], 
            'state' => $data['state'] ?? null,
            'current_state' => $data['current_state'] ?? null,
            'alternate_state' => $data['alternate_state'] ?? null,
            'current_district' => $data['current_district'],
            'district' => $data['district'],
            'alternate_district' => $data['alternate_district'],
            'current_taluk' => $data['current_taluk'],
            'taluk' => $data['taluk'],
            'alternate_taluk' => $data['alternate_taluk'],
            'current_village' => $data['current_village'],
            'village' => $data['village'],
            'alternate_village' => $data['alternate_village'],
            'issued_date' => $data['issued_date'],
            'current_town' => $data['current_town'],
            'town' => $data['town'],
            'alternate_town' => $data['alternate_town'],
            'old_locker_number' => $data['old_locker_number'],
            'physically' => $data['physically'],
            // 'category' => $data['category'],
            // 'court_case_no' => $data['court_case_no'],
            'doc_no' => $data['doc_no'],
            'survey_no' => $data['survey_no'],
            'dry_land' => $data['dry_land'],
            'wet_land' => $data['wet_land'],
            'unit' => $data['unit'],
            'area' =>  $data['area'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'category_id' => $categories,
            'subcategory_id' => $subcategories,
            'set_id' => $setsAsString,
            
            // ... assign other fields ...
            'created_by' =>  Auth::user()->id,

            // ... continue assigning fields ...
        ]);

        $masterDocData->save();

        // Handle the extra logic for sets if required
        // ...
        // dd($tableName);
        // Update or insert into the dynamic table
        $tableName = explode('|', $data['type'], 2)[1];
        if (Schema::hasTable($tableName)) {
            if (!$doc_id) {
                $newDocumentId =   DB::table($tableName)->insert([
                    'doc_id' => $masterDocData->id, // Assuming 'doc_id' is the column name in the dynamic table
                    'doc_type' => $tableName,
                    'document_name' => $data['name'],

                ]);
            } else {
                DB::table($tableName)->where('doc_id', $doc_id)->update([
                    // Update the fields as necessary
                    'document_name' => $data['name']
                ]);
            }
        }

        $document_data = DB::table($tableName)->where('doc_id', $masterDocData->id)->first();
        //  dd($document_data);

        return [
            'status' => 'success',
            'table_name' => $tableName,
            'id' => $document_data->id,
            'document_data' => $document_data
        ];
    }
}
