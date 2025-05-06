<?php
// File: app/Services/NotificationService.php

namespace App\Services;

use App\Models\{Document_assignment, Alert};
class NotificationService
{

    public function createComplianceNotification($type, $compliance = null, $userId)
    {
        $documentName = optional($compliance->document)->name ?? 'Unknown document type';
        $message = "";

        switch ($type) {
            case 'upcoming':
                $message = "A compliance named {$compliance->name} on document {$documentName} is due in less than 30 days.";
                break;
            case 'updated':
                // Existing logic for 'updated'
                break;
                // Other cases...
        }

        Alert::create([
            'type' => $type,
            'message' => $message,
            'compliance_id' => $compliance ? $compliance->id : null,
            'doc_id' => $compliance->doc_id,
            'created_by' => $userId,
        ]);
    }

    public function createDocumentAssignmentNotification($type, Document_assignment $assignment)
    {

        $assignment->load('receiver', 'documentType');
        $receiverName = $assignment->receiver->name ?? 'Unknown receiver';
        $documentName = $assignment->document->name ?? 'Unknown document';
        $documentTypeName = $assignment->document->document_type_name ?? 'Unknown document type';
        // $message = "A Document has been {$type} named {$documentName} to {$receiverName}";

        if ($type === "accessed") {
            $message = "{$receiverName} has accessed the document {$documentName} of type {$documentTypeName}.";
        } elseif ($type === "denied") {

            $message = "{$receiverName} has been denied accessing the document {$documentName} of type {$documentTypeName}.";
        }

        Alert::create([
            'type' => $type,
            'message' => $message,
            'doc_id' => $assignment->doc_id,
            'document_assignment_id' => $assignment->id,
            'created_by' => 1,   # Auth::user()->id is not working here. It returns null becuase now the notification is allowed when the receiver access the email
        ]);
    }
}
