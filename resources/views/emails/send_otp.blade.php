<!DOCTYPE html>
<html lang="en">

 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Otp to access document received</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>

<body style="margin: 0; padding: 0;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="90%"
        style="border-collapse: collapse; border: 30px solid #e7e7e7;">
        <tbody style="padding:0px 30px; display: block;">
            <tr>
                <td style="padding: 56px 0 24px 26px;height:110px;" >
                    <img src="https://ahobila.kods.app/assets/logo/logo.jpg">
                </td>
            </tr>
            <tr>
                <td height="42"
                    style="padding: 10px 0 4px 24px; color: #000000; font-family: Honeywell Sans Web; font-weight: 800; font-size: 26px;">
                    <b>Hi User</b>
                </td>
            </tr>
            <tr>
                <td
                    style="padding:1px 24px 22px; color:#606060; font-size:14px; font-family:  Arial; line-height: 1.5;">
                    You have requested to access a document provided by LandVault.<br />
                    A One-Time Password (OTP) has been generated. This OTP is time and case sensitive and valid
                    for single user access.<br>
                    {{-- This OTP is time sensitive and valid for 10 minutes. --}}
                </td>

            </tr>
            <tr>
                <td
                    style="padding:1px 24px 22px; color:#606060; font-size:14px; font-weight: 800; font-family:  Arial; line-height: 1.5;">
                    Your One Time Password (OTP) is</td>
            </tr>
            <tr>
                <td style="padding: 0px 25px;">
                    <table border="0" cellpadding="0" cellspacing="0" align="center" class="container" >
                        <tr>
                            <td style="font-size:25px;">
                          {{$otp}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                {{-- <td
                    style="padding:18px 0px 50px 25px; color:#606060; font-size:14px; font-family:  Arial; line-height: 20px;  text-align: left;">
                    Need help? Contact  support. <br>
                    Toll Free No. ##CustomerSupportContact## or<br>
                    email us at <a href="mailto:##DealerSupportEmail##"
                        style="color:#0889c4; font-family:  Arial, Helvetica, sans-serif; text-decoration:none">##DealerSupportEmail##</a>
                    <br><br>

                </td> --}}
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
