<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\State;
use App\Models\Set;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    public function run()
    {

        // $crudActions = [
        //     'create' => '1',
        //     'read' => '2',
        //     'update' => '3',
        //     'delete' => '4',
        // ];


        $permissionsWithCRUD = [
            ['name' => 'profile.edit', 'display_name' => 'View Profile', 'action' => 1],
            ['name' => 'profile.update', 'display_name' => 'Update Profile', 'action' => 3],
            //documents
            ['name' => 'documents.review', 'display_name' => 'Main Document View', 'action' => 2],
            ['name' => 'documents.create_first_step', 'display_name' => 'Add Basic Document Form', 'action' => 2],
            ['name' => 'documents.basic_detail.edit', 'display_name' => 'Update Basic Document Detail', 'action' => 2],
            ['name' => 'documents.updateStatus', 'display_name' => 'Update Document Status', 'action' => 3],

            //sets
            ['name' => 'sets.view', 'display_name' => 'View Sets', 'action' => 2],
            // ['name' => 'sets.viewUpdated', 'display_name' => 'View Sets ', 'action' => 2],
            ['name' => 'sets.add', 'display_name' => 'Add Sets', 'action' => 1],
            ['name' => 'sets.update', 'display_name' => 'Update Sets', 'action' => 3],

            // View document
            ['name' => 'documents.filter', 'display_name' => 'Filter Document', 'action' => 2],
            // ['name' => 'documents.view.first', 'display_name' => 'View Documents By Doc Type(no use)', 'action' => 2],
            ['name' => 'documents.getByType', 'display_name' => 'View Documents by Document Type', 'action' => 2],
            ['name' => 'sets.viewDocuments', 'display_name' => 'View Documents from Sets', 'action' => 2],

            //receivers
            ['name' => 'receivers.index', 'display_name' => 'View Receivers', 'action' => 2],
            ['name' => 'receivers.store', 'display_name' => 'Add Receivers', 'action' => 1],
            ['name' => 'receivers.update', 'display_name' => 'Update Receivers', 'action' => 3],

            //assign document
            //   ['name' => 'documents.assigned.show', 'display_name' => 'View Assigned Documents ', 'action' => 2],
            ['name' => 'documents.assign.toReceiver', 'display_name' => 'Assign Document', 'action' => 1],
            ['name' => 'documents.assigned.toggleStatus', 'display_name' => 'Update Document Assignment Status', 'action' => 3],
            ['name' => 'user.documents.assigned.show', 'display_name' => 'View Assigned Documents', 'action' => 2],

          

            //document types
            ['name' => 'document_types.index', 'display_name' => 'View Document Types', 'action' => 2],
            ['name' => 'document_types.store', 'display_name' => 'Add Document Types', 'action' => 1],

            //document fields
            // ['name' => 'fields.create_first_step', 'display_name' => 'View Document Fields ', 'action' => 2],
            ['name' => 'document_fields.view', 'display_name' => 'View Document Fields', 'action' => 2],
            ['name' => 'document_fields.store', 'display_name' => 'Add Document Fields', 'action' => 1],
            ['name' => 'updateDocumentFieldName.update', 'display_name' => 'Update Document Fields', 'action' => 3],
            
            //configure
            ['name' => 'configure', 'display_name' => 'Configure', 'action' => 2],
            //bulk upload
            ['name' => 'master_data.bulk_upload', 'display_name' => 'View Bulk Upload', 'action' => 2],

            //compliances
            ['name' => 'compliances.index', 'display_name' => 'View Compliances', 'action' => 2],
            ['name' => 'compliances.store', 'display_name' => 'Add Compliances', 'action' => 1],
            ['name' => 'compliances.status_change', 'display_name' => 'Update Compliances Status', 'action' => 3],

            //notifications
            ['name' => 'notifications.index', 'display_name' => 'View Notifications', 'action' => 2],
            
            ['name' => 'users.index', 'display_name' => 'View Users ', 'action' => 2],
            ['name' => 'users.edit', 'display_name' => 'Update Users ', 'action' => 3],
            
            ['name' => 'notifications.compliances', 'display_name' => 'View Compliance Notifications', 'action' => 2],
            ['name' => 'notifications.recipients', 'display_name' => 'View Recipient Notifications', 'action' => 2],
            ['name' => 'compliances.isRecurring.toggle', 'display_name' => 'Update Compliance Recurring Status', 'action' => 3],
        
            ['name' => 'logs.action-logs', 'display_name' => 'Action Logs', 'action' => 2],
            ['name' => 'logs.http-request-logs', 'display_name' => 'Http Request Logs', 'action' => 2],
            
            ['name' => 'soldLand.view', 'display_name' => 'View Sold Land', 'action' => 2],
            ['name' => 'soldLand.store', 'display_name' => 'Add Sold Land', 'action' => 1],
            ['name' => 'soldLand.update', 'display_name' => 'Update Sold Land', 'action' => 3],
            ['name' => 'documents.viewUploadedDocuments', 'display_name' => 'View Uploaded PDF', 'action' => 2],
            ['name' => 'upload.files', 'display_name' => 'Add PDF', 'action' => 1],
            ['name' => 'documents.delete', 'display_name' => 'Delete PDF', 'action' => 3],

             //advocates
             ['name' => 'advocates.store', 'display_name' => 'View Advocates', 'action' => 1],
             ['name' => 'advocates.index', 'display_name' => 'Add Advocates', 'action' => 2],
             ['name' => 'advocates.update', 'display_name' => 'Update Advocates', 'action' => 3],
 
             //assign docs to advocates
             
             ['name' => 'documents.assign.toAdvocate', 'display_name' => 'Add Assigned Docs to Advocate', 'action' => 1],
             ['name' => 'advocate.documents.assigned.show', 'display_name' => 'View Assigned Docs to Advocate', 'action' => 2],
             ['name' => 'documentAdvocateAssignment.update', 'display_name' => 'Update Assigned Docs to Advocate', 'action' => 4],
             ['name' => 'documentAdvocateAssignment.destroy', 'display_name' => 'Delete Assigned Docs to Advocate', 'action' => 5],
 
             //document transaction logs
             ['name' => 'storeTransaction', 'display_name' => 'Add Document Logs', 'action' => 1],
             ['name' => 'documentTransaction.show', 'display_name' => 'View Document Logs', 'action' => 2],
             ['name' => 'updateTransaction', 'display_name' => 'Update Document Logs', 'action' => 3],
             ['name' => 'destroyTransaction', 'display_name' => 'Delete Document Logs', 'action' => 5],
        ];

   


        foreach ($permissionsWithCRUD as $permissionData) {
            Permission::insert([
                'name' => $permissionData['name'],
                'display_name' => $permissionData['display_name'],
                'action' => $permissionData['action'],
                // You might want to include a timestamp for created_at and updated_at
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
