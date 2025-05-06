<?php

use App\Http\Controllers\{NotificationController, ReceiverController, DocumentController, SetController, UserController, ComplianceController, DashboardController, BulkUploadController, ReceiverProcessController, ProfileController, FilterDocumentController, LogController, SoldLandController, ProjectSettingsController, AdvocateController, DataSetController, ReportController};
use Illuminate\Support\Facades\Route;
use App\Exports\CompliancesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

Route::view('/error/403', 'error.403')->name('error');
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
});

Route::get('/verify-document/{token}', [ReceiverProcessController::class, 'showPublicDocument'])->name('showPublicDocument');
Route::get('/otp/{token}', [ReceiverProcessController::class, 'showOtpForm'])->name('otp.form');
Route::post('/verify-document/{token}', [ReceiverProcessController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/send-otp', [ReceiverProcessController::class, 'sendOTP'])->name('otp.send');

Route::middleware(['auth', 'verified', 'checkuserpermission', 'xss-protection', 'LogHttpRequest'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //error page
    Route::view('/error-page-403', 'pages.error-403')->name('error-403');

    // set view
    Route::get('/set', [SetController::class, 'viewSet'])->name('sets.view');
    Route::get('/get-updated-sets', [SetController::class, 'viewUpdatedSets'])->name('sets.viewUpdated');
    Route::post('/add_set', [SetController::class, 'addSet'])->name('sets.add');
    Route::post('/update-set', [SetController::class, 'updateSet'])->name('sets.update');

    //reports
    Route::get('/child-document-reports', [ReportController::class, 'childDocumentReports'])->name('childDocumentReports.index');
    Route::post('/child-filter-document', [ReportController::class, 'childDocumentReports'])->name('childDocumentReports.review');
    Route::post('/child-export-documents', [ReportController::class, 'exportFilteredDocuments'])->name('childDocuments.export');
    Route::get('/documents-assigned-to-receivers', [ReportController::class, 'documentsAssignedToReceivers'])->name('documentsAssignedToReceivers.index');
    Route::get('/assignedDocumentsToReceivers/export', [ReportController::class, 'documentsAssignedToReceiversExport'])->name('assignedDocumentsToReceivers.export');
    Route::get('/documents-assigned-to-advocates', [ReportController::class, 'documentsAssignedToAdvocates'])->name('documentsAssignedToAdvocates.index');
    // assignedDocumentsToAdvocates
    Route::get('/assignedDocumentsToAdvocates/export', [ReportController::class, 'documentsAssignedToAdvocatesExport'])->name('assignedDocumentsToAdvocates.export');
    Route::get('/compliances/export', [ComplianceController::class, 'compliancesExport'])->name('compliances.export');

    // Route::get('/compliances/export', function (Request $request) {
    //     return Excel::download(new CompliancesExport($request->all()), 'compliances.xlsx');
    // })->name('compliances.export');


    // Route::get('/assignedDocumentsToReceivers/export', [ReportController::class, 'export'])->name('assignedDocumentsToReceivers.export');
    //in the receivers page
    Route::get('/export-receivers', [ReceiverController::class, 'exportReceivers'])->name('receivers.export');
    Route::get('/export-advocates', [AdvocateController::class, 'exportAdvocates'])->name('advocates.export');

    Route::post('/export-documents', [FilterDocumentController::class, 'exportFilteredDocuments'])->name('documents.export');

    // receivers
    Route::get('/receivers', [ReceiverController::class, 'showReceivers'])
        ->name('receivers.index');

    Route::post('/add-receiver', [ReceiverController::class, 'addReceiver'])
        ->name('receivers.store');
    Route::post('/update-receiver', [ReceiverController::class, 'updateReceiver'])
        ->name('receivers.update');
    Route::get('/get-receivers/{typeId}', [ReceiverProcessController::class, 'getReceiversByType'])
        ->name('receivers.byType');
    Route::get('/get-active-receivers/{typeId}', [ReceiverProcessController::class, 'getActiveReceiversByType'])
        ->name('activeReceivers.byType');
    Route::get('/get-updated-receivers', [ReceiverController::class, 'getUpdatedReceivers'])
        ->name('receivers.updated');

    // assigning documents
    Route::get('/assign-documents', [ReceiverProcessController::class, 'showAssignedDocument'])
        ->name('documents.assigned.show');
    Route::get('/user-assign-documents/{receiver_id}', [ReceiverProcessController::class, 'showUserAssignedDocument'])
        ->name('user.documents.assigned.show');
    Route::post('/toggle-assigned-document-status/{id}', [ReceiverProcessController::class, 'toggleStatus'])
        ->name('documents.assigned.toggleStatus');
    Route::post('/assign-documents-to-receiver', [ReceiverProcessController::class, 'assignDocumentsToReceiver'])
        ->name('documents.assign.toReceiver');

    //document type
    Route::get('/document_type', [DocumentController::class, 'document_type'])->name('document_types.index');
    Route::post('/add_document_type', [DocumentController::class, 'addDocumentType'])->name('document_types.store');

    Route::post('/add_document_field', [DocumentController::class, 'add_document_field'])->name('document_fields.store');
    Route::get('/document_field/{table?}', [DocumentController::class, 'document_field'])->name('document_fields.view');
    //update dynamic document field name
    Route::put('/edit_document_field/{tableName}/{id}', [DocumentController::class, 'updateDocumentFieldName'])->name('updateDocumentFieldName.update');
    //documents
    Route::post('/add_document', [DocumentController::class, 'add_document'])
        ->name('documents.store');

    Route::get('/add_document_first', [DocumentController::class, 'add_document_first'])
        ->name('documents.add_document_first');


    Route::get('/review_doc/{table}/{id}', [DocumentController::class, 'review_doc'])
        ->name('documents.review.detail');
    Route::post('/add-document-data', [DocumentController::class, 'add_document_data'])
        ->name('documents.data.add');
    Route::put('/update-first-document-data/{id}', [DocumentController::class, 'updateFirstDocumentData'])
        ->name('documents.data.first.update');
    Route::get('/document-creation-continue', [DocumentController::class, 'documentCreationContinue'])
        ->name('documents.creation.continue');

    Route::get('/edit_document_basic_detail/{id}', [DocumentController::class, 'edit_document_basic_detail'])
        ->name('documents.basic_detail.edit');
    Route::post('/update_document', [DocumentController::class, 'update_document'])
        ->name('documents.updateStatus');
    // Route::post('/update_document', [DocumentController::class, 'update_document'])
    //     ->name('documents.updateStatus');
    Route::put('/update-status-message/{log}', [DocumentController::class, 'updateStatusMessage'])->name('documents.statusMessage');
    // Route::put('/update-status-message/{log}', 'DocumentController@updateStatusMessage')->name('update.statusMessage');

    //view documents
    Route::GET('/filter-document', [FilterDocumentController::class, 'filterDocument'])->name('documents.review');
    Route::post('/filter-document', [FilterDocumentController::class, 'filterDocument'])->name('documents.review');
    // Route::post('/documents/export', [FilterDocumentController::class, 'export'])->name('documents.export');

    Route::get('/documents-for-set/{setId}', [SetController::class, 'viewDocumentsForSet'])->name('sets.viewDocuments');
    Route::get('/view-uploaded-documents/{page?}', [DocumentController::class, 'viewUploadedDocuments'])->name('documents.viewUploadedDocuments');
    Route::delete('/documents/{filename}', [DocumentController::class, 'deleteFile'])->name('documents.delete');
    Route::post('/upload-files', [DocumentController::class, 'uploadFiles'])->name('upload.files');

    //ajax call to get the documen from doc_type
    Route::get('/get-documents/{typeId}', [BulkUploadController::class, 'getDocumentsByType'])->name('documents.getByType');

    Route::get('/api/fetch/{type}/{id}/{isStatus}', [BulkUploadController::class, 'fetchData']);

    //data sets start
    Route::get('/data-sets', [DataSetController::class, 'configure'])->name('configure');
    //receiver type
    Route::get('/receiver-type', [DataSetController::class, 'receiverType'])->name('receiverTypes.view');
    Route::post('/add-receiver-type', [DataSetController::class, 'addReceiverType'])->name('receiverTypes.add');
    Route::post('/update-receiver-type', [DataSetController::class, 'updateReceiverType'])->name('receiverTypes.update');
    //category
    Route::get('/categories', [DataSetController::class, 'showCategories'])->name('categories.show');
    Route::post('/categories', [DataSetController::class, 'addCategory'])->name('categories.add');
    Route::put('/categories', [DataSetController::class, 'updateCategory'])->name('categories.update');
    // Subcategory routes
    Route::get('/subcategories', [DataSetController::class, 'showSubcategories'])->name('subcategories.show');
    Route::post('/subcategories', [DataSetController::class, 'addSubcategory'])->name('subcategories.add');
    Route::put('/subcategories', [DataSetController::class, 'updateSubcategory'])->name('subcategories.update');

    //data sets end

    //bulk upload documents 
    Route::get('/bulk-upload-master-data', [BulkUploadController::class, 'bulkUploadMasterData'])
        ->name('master_data.bulk_upload');
    Route::post('/bulk-upload-master-document-data', [BulkUploadController::class, 'bulkUploadMasterDocumentData'])
        ->name('master_documents.bulk_upload');
    Route::post('/bulk-upload-child-document-data', [BulkUploadController::class, 'bulkUploadChildDocumentData'])
        ->name('child_documents.bulk_upload');
    Route::post('/bulk-upload-single-data-update', [BulkUploadController::class, 'bulkUpdateDocumentData'])
        ->name('master_documents.bulk_update');

    //compliance routes
    Route::get('/compliances', [ComplianceController::class, 'showCompliances'])
        ->name('compliances.index');
    Route::post('/create-compliances', [ComplianceController::class, 'store'])
        ->name('compliances.store');
    Route::post('/status-change-compliance/{id}/{action}', [ComplianceController::class, 'statusChangeCompliance'])
        ->name('compliances.status_change');
    //to change the status of is recurring of the COompliances
    Route::post('/toggle-compliances-is-recurring/{id}', [ComplianceController::class, 'toggleIsRecurring'])
        ->name('compliances.isRecurring.toggle');

    //notifications
    Route::get('/notifications', [NotificationController::class, 'showNotifications'])
        ->name('notifications.index');

    //user
    //users//subadmin
    Route::get('/users', [UserController::class, 'showUsers'])
        ->name('users.index');
    Route::get('/users/{id}/reviewed-documents', [UserController::class, 'showReviewedDocumentsUsers'])
        ->name('users.show_reviewed_documents_users');

    Route::post('/register-user', [UserController::class, 'store'])
        ->name('users.store');
    // Display the edit form

    Route::get('/users/{user}/edit', [UserController::class, 'showUsers'])->name('users.edit');

    // Process the update form submission
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/sold-land/{id?}', [SoldLandController::class, 'storeOrUpdate'])->name('soldLand.storeOrUpdate');

    Route::get('/sold-land/export', [SoldLandController::class, 'exportSoldLand'])->name('soldLand.export');
    Route::get('/sold-land', [SoldLandController::class, 'view'])->name('soldLand.view');
    Route::get('/sold-land-actions', [SoldLandController::class, 'add'])->name('soldLand.add');
    //show single page
    Route::get('/sold-land/{soldLand}/edit', [SoldLandController::class, 'edit'])->name('soldLand.edit');
    //add sold land details
    Route::post('/add-sold-land', [SoldLandController::class, 'store'])->name('soldLand.store');
    //single sold land details
    Route::get('/sold-land/{soldLand}', [SoldLandController::class, 'show'])->name('soldLand.show');
    //update
    Route::post('/sold-land/{soldLand}', [SoldLandController::class, 'update'])->name('soldLand.update');
    //add or update bulk upload 
    Route::post('/bulk-upload-sold-land-data', [SoldLandController::class, 'bulkUploadSoldLandData'])
        ->name('sold_land.bulk_upload');
    //Project settings
    Route::get('/project-settings/edit', [ProjectSettingsController::class, 'edit'])->name('project-settings.edit');
    Route::put('/project-settings', [ProjectSettingsController::class, 'update'])->name('project-settings.update');


    // advocates
    Route::get('/advocates', [AdvocateController::class, 'showAdvocates'])
        ->name('advocates.index');
    Route::post('/add-advocate', [AdvocateController::class, 'addAdvocate'])
        ->name('advocates.store');
    Route::post('/update-advocate', [AdvocateController::class, 'updateAdvocate'])
        ->name('advocates.update');
    Route::post('/bulk-upload-advocate-assign-document', [AdvocateController::class, 'bulkUploadAdvocateAssignDocument'])
        ->name('documentToAdvocate.bulk_upload');

    // assigning documents to advocate

    Route::get('/advocate-assign-documents/{advocate_id}', [AdvocateController::class, 'showAdvocateAssignedDocument'])
        ->name('advocate.documents.assigned.show');
    Route::post('/assign-documents-to-advocate', [AdvocateController::class, 'assignDocumentsToAdvocate'])->name('documents.assign.toAdvocate');
    Route::put('/document-assignment/{id}', [AdvocateController::class, 'updateDocumentAssignment'])->name('documentAdvocateAssignment.update');
    Route::get('/document-assignment/{id}/edit', [AdvocateController::class, 'editDocumentAssignment'])->name('documentAdvocateAssignment.edit');
    Route::delete('/document-assignment/{id}', [AdvocateController::class, 'destroy'])->name('documentAdvocateAssignment.destroy');

    //document transaction logs
    Route::get('/document-transactions/{id}', [DocumentController::class, 'getDocumentTransactionById'])->name('documentTransaction.show');
    Route::post('/document-transactions', [DocumentController::class, 'storeTransaction']);
    // Route::put('/document-transactions/{id}', [DocumentController::class, 'updateTransaction']);
    // Route::delete('/document-transactions/{id}', [DocumentController::class, 'destroyTransaction'])->name('documentTransaction.destroy');
    Route::put('/document-transactions/{id}', [DocumentController::class, 'updateTransaction'])->name('documentTransaction.update');
    Route::get('/document-transactions', [DocumentController::class, 'showDocumentTransactions'])->name('document.transactions');

    // Route::get('/document-transactions/{id}/edit', [AdvocateController::class, 'getDocumentTransactionById'])->name('getDocumentTransactionByIdAPI.edit');

    Route::get('/api/fetch/districts/{state}', [DocumentController::class, 'fetchDistricts']);
    Route::get('/api/fetch/villages/{district}', [DocumentController::class, 'fetchVillages']);
    Route::get('/api/fetchForSold/districts/{state}', [DocumentController::class, 'fetchDistrictsForSold']);
    Route::get('/api/fetchForSold/villages/{district}', [DocumentController::class, 'fetchVillagesForSold']);
    Route::get('/action-logs', [LogController::class, 'actionLogsIndex'])->name('logs.action-logs');
    Route::get('/http-request-logs', [LogController::class, 'httpRequestLogs'])->name('logs.http-request-logs');
});

require __DIR__ . '/auth.php';
