<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Document Assigned </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>

<body style="margin: 0; padding: 0;">
    <style>
        <style>
    @media only screen and (max-width: 600px) {
		.main {
			width: 320px !important;
		}

		.top-image {
			width: 100% !important;
		}
		.inside-footer {
			width: 320px !important;
		}
		table[class="contenttable"] { 
            width: 320px !important;
            text-align: left !important;
        }
        td[class="force-col"] {
	        display: block !important;
	    }
	     td[class="rm-col"] {
	        display: none !important;
	    }
		.mt {
			margin-top: 15px !important;
		}
		*[class].width300 {width: 255px !important;}
		*[class].block {display:block !important;}
		*[class].blockcol {display:none !important;}
		.emailButton{
            width: 100% !important;
        }

        .emailButton a {
            display:block !important;
            font-size:18px !important;
        }

	}
</style>
    </style>
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="70%"
        style="border-collapse: collapse; border: 30px solid #e7e7e7;">
        <tbody style="padding:0px 10px; display: block;">
            <tr>
                <td style="padding: 56px 0 24px 26px;height:75px;text-align: center;">
                    <img src="https://ahobila.kods.app/assets/logo/logo.jpg" style="height:100px;width:55%;">
                </td>
            </tr>
            <tr>
                <td height="42"
                    style="padding: 10px 0 4px 24px; color: #000000; font-family: Honeywell Sans Web; font-weight: 800; font-size: 26px;">
                    <b>Hello, {{ $receiverName }}</b>
                </td>
            </tr>

            <tr>
                <td class="text"
                    style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 24px;">
                    <div class="mktEditable" id="download_button" style="text-align: center;">
                        <a style="color:#ffffff; background-color: #ff8300; border: 20px solid #ff8300; border-left: 20px solid #ff8300; border-right: 20px solid #ff8300; border-top: 10px solid #ff8300; border-bottom: 10px solid #ff8300;border-radius: 3px; text-decoration:none;"
                            href="{{ $verificationUrl }}">Verify and Access Document</a>
                    </div>
                </td>

            </tr>
        <br/>
            <tr>
                <td
                    style="padding:1px 24px 22px; color:#606060; font-size:14px; font-family:  Arial; line-height: 1.5;">
                    You have requested to access a document provided by LandVault.<br />
                    A One-Time Password (OTP) has been generated. This OTP is time and case sensitive and valid time
                    sensitive and valid for 48 hours.<br>
                    {{-- This OTP is time sensitive and valid for 10 minutes. --}}
                </td>

            </tr>
            <tr>
                <td
                    style="padding:1px 24px 22px; color:#606060; font-size:14px; font-weight: 800; font-family:  Arial; line-height: 1.5;">
                    Your One Time Password (OTP) is </td>
            </tr>
            <tr>
                <td style="padding: 0px 25px;">
                    <table border="0" cellpadding="0" cellspacing="0" align="center" class="container">
                        <tr>
                            <td style="font-size:25px;">
                                {{ $otp }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            {{-- <tr>
                <td
                    style="padding:18px 0px 50px 25px; color:#606060; font-size:14px; font-family:  Arial; line-height: 20px;  text-align: left;">
                    Need help? Contact  support. <br>
                    Toll Free No. ##CustomerSupportContact## or<br>
                    email us at <a href="mailto:##DealerSupportEmail##"
                        style="color:#0889c4; font-family:  Arial, Helvetica, sans-serif; text-decoration:none">##DealerSupportEmail##</a>
                    <br><br>

                </td>
            </tr> --}}
            <tr bgcolor="#fff" style="border-top: 4px solid #00a5b5;">
                <td valign="top" class="footer"
                    style="border-collapse: collapse; border: 0; margin: 0; padding: 0; -webkit-text-size-adjust: none; color: #555559; font-family: Arial, sans-serif; font-size: 16px; line-height: 26px; background: #fff; text-align: left;">
                    <table
                        style="font-weight: normal; border-collapse: collapse; border: 0; margin: 0; padding: 0; font-family: Arial, sans-serif;">
                        <tr>
                            <td class="inside-footer" align="left" valign="middle"
                                style="border-collapse: collapse; border: 0; margin: 0; padding: 20px; -webkit-text-size-adjust: none; color: #555559; font-family: Arial, sans-serif; font-size: 12px; line-height: 16px; vertical-align: middle; text-align: left; width: 580px;">
                                <div id="address" class="mktEditable">
                                    Warm regards,<br>
                                    <b>LandVault</b> <br> 8-A, 1st St, East Tambaram, Tambaram, Chennai, Tamil Nadu 600059<br>
                                    {{-- <a style="color: #00a5b5;" href="https://www.ahobila.kods.app">Contact Us</a> --}}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
        </tbody>
        <tr>
            <td
                style="padding:28px 0 0; color: #606060; font-family:  Arial; font-size: 12px; background-color: #e7e7e7;">
                &copy; LandVault. All Rights Reserved<br><br>
                The content of this message, together with any attachments, are intended only for the use of the
                person(s) to which they are addressed and may contain confidential and/or privileged information.
                Further, any information herein is confidential and protected by law. It is unlawful for unauthorized
                persons to use, review, copy, disclose, or disseminate confidential information. If you are not the
                intended recipient, immediately advise the sender and delete this message and any attachments. Any
                distribution, or copying of this message, or any attachment, is prohibited.
            </td>
        </tr>
    </table>
</body>

</html>
