<?php

namespace App\Http\Controllers;
// namespace App\Mail;

use Illuminate\Http\Request;


use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment};
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\AssignDocumentEmail;
use App\Mail\SendOtpMail;
use App\Services\NotificationService;

class ReceiverProcessController extends Controller
{

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function showAssignedDocument()
    {
        $documentAssignments = Document_assignment::with(['receiver', 'receiverType', 'documentType', 'document'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        $documentTypes = Master_doc_type::orderBy('name')->get();
        $receiverTypes = Receiver_type::where('status', 1)->get();
    
        // Process each document assignment to retrieve the child_id
        $processedDocumentAssignments = $documentAssignments->map(function ($assignment) {
            $documentTypeName = $assignment->documentType->name;
    
            // Build the table name dynamically
            $childDocument = DB::table($documentTypeName)
                ->where('doc_id', $assignment->doc_id)
                ->first();
    
            if ($childDocument) {
                $assignment->child_id = $childDocument->id;
            }
    
            return $assignment;
        });
    
        return view('pages.assign-document.assign-documents', [
            'documentAssignments' => $processedDocumentAssignments,
            'documentTypes' => $documentTypes,
            'receiverTypes' => $receiverTypes
        ]);
    }
    

//     public function showUserAssignedDocument($receiverId)
//     {
//         // Filter the document assignments by the passed receiver ID
//         $documentAssignments = Document_assignment::with(['receiver', 'receiverType', 'documentType', 'document'])
//             ->where('receiver_id', $receiverId)
//             ->orderBy('created_at', 'desc')
//             ->get();

//         // If you still need the lists of document types and receiver types for dropdowns or other UI elements
//         $documentTypes = Master_doc_type::all();
//         $receiverTypes = Receiver_type::where('status', 1)->get();

//         // You can also get the receiver details if needed, for example to display their name on the page
//         $receiver = Receiver::find($receiverId);
// // dd($documentAssignments);
//         return view('pages.assign-document.user-document-assignments', [
//             'documentAssignments' => $documentAssignments,
//             'documentTypes' => $documentTypes,
//             'receiverTypes' => $receiverTypes,
//             'receiver' => $receiver, // Pass the receiver details to the view if needed
//         ]);
//     }

public function showUserAssignedDocument($receiverId)
{
    // Filter the document assignments by the passed receiver ID
    $documentAssignments = Document_assignment::with(['receiver', 'receiverType', 'documentType', 'document'])
        ->where('receiver_id', $receiverId)
        ->orderBy('created_at', 'desc')
        ->get();

    // Retrieve the lists of document types and receiver types for dropdowns or other UI elements
    $documentTypes = Master_doc_type::all();
    $receiverTypes = Receiver_type::where('status', 1)->get();

    // Retrieve the receiver details
    $receiver = Receiver::find($receiverId);

    // Process each document assignment to retrieve the child_id
    $processedDocumentAssignments = $documentAssignments->map(function ($assignment) {
        $documentTypeName = $assignment->documentType->name;

        // Build the table name dynamically
        $childDocument = DB::table($documentTypeName)
            ->where('doc_id', $assignment->doc_id)
            ->first();

        if ($childDocument) {
            $assignment->child_id = $childDocument->id;
        }

        return $assignment;
    });
// dd($processedDocumentAssignments);
    // Return the view with the processed document assignments
    return view('pages.assign-document.user-document-assignments', [
        'documentAssignments' => $processedDocumentAssignments,
        'documentTypes' => $documentTypes,
        'receiverTypes' => $receiverTypes,
        'receiver' => $receiver,
    ]);
}



    public function getReceiversByType($typeId)
    {
        $receivers = Receiver::where('receiver_type_id', $typeId)->get();
        return response()->json(['receivers' => $receivers]);
    }
    public function getActiveReceiversByType($typeId)
    {
        $receivers = Receiver::where('receiver_type_id', $typeId)->where('status',true)->get();
        return response()->json(['receivers' => $receivers]);
    }

    public function assignDocumentsToReceiver(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'document_type' => 'required', // Assuming this is an ID or a code
            'document_id' => 'required', // Assuming documents table exists
            'receiver_id' => 'required', // Assuming receivers table exists
            'receiver_type' => 'required', // Assuming receivers table exists
        ]);

        $location = $request->location;
        // Generate a unique token with the current timestamp
        $timestamp = Carbon::now()->timestamp;
        $token = Str::random(40) . '_' . $timestamp;
        $expiresAt = Carbon::now()->addHours(48);
        // $receiverId = $validatedData['receiver_id'];
        // Create a new document assignment entry
        $otp = rand(1000, 9999);
        $receiver = Receiver::find($validatedData['receiver_id']);
        if (!$receiver || $receiver->status != 1) {
            session()->flash('toastr', ['type' => 'error', 'message' => 'Receiver is not active.']);
            return redirect()->back();
        }
        // dd($otp);
        $assignment = Document_assignment::create([
            'document_type' => $validatedData['document_type'],
            'doc_id' => $validatedData['document_id'],
            'receiver_id' => $validatedData['receiver_id'],
            'receiver_type' => $validatedData['receiver_type'],
            'access_token' => $token,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'created_by' => Auth::user()->id,
        ]);

        if ($assignment->wasRecentlyCreated) {
            $receiver = Receiver::findOrFail($validatedData['receiver_id']);
            $receiverEmail = $receiver->email; // Assuming the 'email' column exists in the receivers table
            $receiverName = $receiver->name; // Assuming the 'email' column exists in the receivers table
            $verificationUrl = url('/otp/' . $token);

            if ($this->sendAssignmentEmail($receiverEmail, $verificationUrl, $expiresAt, $receiverName, $otp)) {
                session()->flash('toastr', ['type' => 'success', 'message' => 'Documents assigned successfully. Verification email sent.']);
            } else {
                session()->flash('toastr', ['type' => 'error', 'message' => 'Receiver email not found.']);
            }

            // Redirect with success message
            session()->flash('toastr', ['type' => 'success', 'message' => 'Documents assigned successfully. Verification email sent.']);
            if ($location == "all") {
                return redirect('/assign-documents');
            } else {
                // If receiver_id is a part of the request, you can get it like this:
                $receiverId = $request->input('receiver_id');
               // return redirect('/user-assign-documents/' . $receiverId);
            return redirect()->back()->with('success', 'File deleted successfully.');

            }
        } else {
            // Handle the case where the assignment was not created
            session()->flash('toastr', ['type' => 'warning', 'message' => 'Assignment could not be created']);
            if ($location == "all") {
                return redirect('/assign-documents');
            } else {
                // If receiver_id is a part of the request, you can get it like this:
                $receiverId = $request->input('receiver_id');
            return redirect()->back()->with('success', 'File deleted successfully.');

               // return redirect('/user-assign-documents/' . $receiverId);
            }
        }
    }


    public function sendAssignmentEmail($receiverEmail, $verificationUrl, $expiresAt, $receiverName, $otp)
    {
        if (!$receiverEmail) {
            // Handle the case where the email is not set
            session()->flash('toastr', ['type' => 'error', 'message' => 'Receiver email not found.']);
            return redirect('/assign-documents');
        }

        Mail::to($receiverEmail)->send(new AssignDocumentEmail($verificationUrl, $expiresAt, $receiverName, $otp));
        return true;
    }



    public function showPublicDocument($token)
    {


        // dd("adfsdf");
        if (session()->has('otp_validated') && session()->get('otp_validated') === $token) {
            // Clear the OTP validation from the session so it's required next time
            session()->forget('otp_validated');

            $assignment = Document_assignment::where('access_token', $token)
                ->where('expires_at', '>', now())
                ->first();
                // ->where('status', '1')
            // dd($assignment->receiver->status);
           
            if (!$assignment) {
                return redirect()->route('error')->with('error', 'Document not found.');
            }
            

            if ( $assignment->receiver->status != '1' || $assignment->status==0) {
                // dd("here");
                $this->notificationService->createDocumentAssignmentNotification('denied', $assignment);

                abort(404, 'Document not found, link has expired, or receiver is inactive.');
            }
            // if (!$assignment) {
            //     abort(404, 'Document not found or link has expired.');
            // }

            $documentType = Master_doc_type::findOrFail($assignment->document_type)->name;
            $documentData = Master_doc_data::findOrFail($assignment->doc_id);

            $tableMetadata = Table_metadata::where('table_id', $assignment->document_type)
                ->whereIn('data_type', [3, 4, 6])
                ->get();

            $filePaths = [];
            foreach ($tableMetadata as $metadata) {
                $columnName = $metadata->column_name;
                $filePath = DB::table($documentType)->where('doc_id', $assignment->doc_id)->value($columnName);
                if ($filePath) {
                    $filePaths[$metadata->data_type] = $filePath;
                }
            }
            // Retrieve the default PDF file path
            $defaultPdfPath = DB::table($documentType)->where('doc_id', $assignment->doc_id)->value('pdf_file_path');

            // Check if default PDF path exists and add it to the file paths array
            if ($defaultPdfPath) {
                $filePaths['default_pdf'] = $defaultPdfPath;
            }
            // dd($defaultPdfPath);
            // Optionally update the database to indicate that the document has been viewed
            if (is_null($assignment->first_viewed_at)) {
                $assignment->first_viewed_at = now();
                $assignment->first_viewed_ip = request()->ip(); // Capture the IP address
            }

            $assignment->view_count = $assignment->view_count + 1; // Increment the view count
            $assignment->save();

            $this->notificationService->createDocumentAssignmentNotification('accessed', $assignment);

            // Serve the document details to a view
            return view('emails.show', [
                'filePaths' => $filePaths,
                'documentName' => $documentData->name,
            ]);
        } else {
            // Initialize $assignment to null
            $assignment = Document_assignment::where('access_token', $token)
                ->where('expires_at', '>', now())
                ->where('status', '1')
                ->first();
            if ($assignment) {
                $this->notificationService->createDocumentAssignmentNotification('denied', $assignment);
            }
            return redirect()->route('otp.form', ['token' => $token]);
        }
    }

    //so, on changing the status new email will be sent with new otp and xpiry time

    public function toggleStatus(Request $request, $id)
    {
        $assignment = Document_assignment::findOrFail($id);

        // Deactivate the assignment
        if ($assignment->status) {
            $assignment->status = 0;
            $assignment->save();
            session()->flash('toastr', ['type' => 'error', 'message' => 'Document assignment deactivated successfully.']);
            return redirect()->back()->with('success', 'Document assignment deactivated successfully.');
        }

        // Reactivate the assignment - update OTP and expiry
        else {
            $newOtp = rand(1000, 9999); // Generate a new OTP
            $assignment->otp = $newOtp;
            $assignment->expires_at = Carbon::now()->addHours(48); // Set new expiry
            $assignment->status = true; // Set status to active
            $assignment->save();

            // Fetch receiver details
            $receiver = Receiver::findOrFail($assignment->receiver_id);
            $receiverEmail = $receiver->email;
            $receiverName = $receiver->name;
            $verificationUrl = url('/otp/' . $assignment->access_token);
            $expiresAt = $assignment->expires_at;

            // Send the email
            $this->sendAssignmentEmail($receiverEmail, $verificationUrl, $expiresAt, $receiverName, $newOtp);

            session()->flash('toastr', ['type' => 'success', 'message' => 'Document assignment reactivated with a new OTP and extended expiry time.']);
            return redirect()->back()->with('success', 'Document assignment reactivated with a new 48-hour expiry.');
        }
    }


    public function showOtpForm($token)
    {

        $assignment = Document_assignment::where('access_token', $token)->first();
        if ($assignment) {
            // Fetch receiver details using receiver_id
            $receiver = Receiver::find($assignment->receiver_id);
            if ($receiver) {
                // Pass receiver details along with token to the view
                return view('emails.otp_form', [
                    'token' => $token,
                    'receiverName' => $receiver->name,
                    'receiverEmail' => $receiver->email
                ]);
            }
        }

        // You can pass the token to the view if you want to keep track of which document the OTP is for
        return view('emails.otp_form', ['token' => $token]);
    }

    public function verifyOtp(Request $request, $token)
    {
        // Retrieve the document assignment using the token
        $documentAssignment = Document_assignment::where('access_token', $token)->firstOrFail();

        // Check if OTP is already verified or not set
        if (empty($documentAssignment->otp)) {
            return redirect()->back()->withErrors(['otp' => 'OTP is already verified or not set.']);
        }

        // Get the input OTP from the request
        $inputOtp = $request->input('otp');
        // Check if the input OTP matches the OTP stored in the database
        if ($inputOtp == $documentAssignment->otp) {
            // Mark the OTP as validated in the session
            session()->put('otp_validated', $token);

            // Clear the OTP from the document_assignment to prevent re-verification
            // $documentAssignment->otp = null;
            $documentAssignment->save();

            // Redirect to the document viewing page
            return redirect()->route('showPublicDocument', ['token' => $token]);
        } else {
            // If OTP is wrong, redirect back with an error message
            return redirect()->back()->withErrors(['otp' => 'The OTP entered is incorrect.']);
        }
    }

    public function sendOTP(Request $request)
    {
        // Validate the token and find the corresponding document assignment
        $documentAssignment = Document_assignment::where('access_token', $request->token)->firstOrFail();

        // Generate a random 4-digit OTP
        $otp = rand(1000, 9999);

        // Save the OTP to the document_assignment table
        $documentAssignment->otp = $otp;
        $documentAssignment->save();

        // Retrieve receiver's email from the receiver_id
        $receiverEmail = $documentAssignment->receiver->email; // Assuming a relationship is set up
        // dd($receiverEmail);
        // Send OTP to the receiver's email
        Mail::to($receiverEmail)->send(new SendOtpMail($otp));

        // Redirect back or to a specific page
        return back()->with(['message' => 'OTP sent to the receiver\'s email.']);
    }
}
