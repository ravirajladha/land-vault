<?php 
// SendOtpMail.php in app/Mail
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->view('emails.send_otp')
                    ->with([
                        'otp' => $this->otp,
                    ]);
    }
}

?>