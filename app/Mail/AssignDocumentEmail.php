<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AssignDocumentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;
    public $expiresAt;
    public $receiverName;
    public $otp;

    /**
     * Create a new message instance.
     *
     * @param string $verificationUrl The URL the user will click to verify and access the document
     * @param string $receiverName The URL the user will click to verify and access the document
     * @param string $otp The URL the user will click to verify and access the document
     * @param Carbon $expiresAt The expiration time of the verification URL
     */
    public function __construct($verificationUrl, Carbon $expiresAt,$receiverName,$otp)
    {
        $this->verificationUrl = $verificationUrl;
        $this->expiresAt = $expiresAt;
        $this->receiverName = $receiverName;
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * This method returns $this to allow for method chaining.
     */
    public function build()
    {
        return $this->subject('Assigned Document')
                    ->view('emails.assign_document_sample')
                    ->with([
                        'verificationUrl' => $this->verificationUrl,
                        'receiverName' => $this->receiverName,
                        'otp' => $this->otp,
                        'expiresAt' => $this->expiresAt->format('M d, Y, g:i A') // Custom format// Format the expiration time as needed
                    ]);
    }
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Assign Document Email test',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'emails.assign_document_sample',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
