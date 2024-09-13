<!DOCTYPE html>
<html>

<head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    /* Reset default margin and padding */
    body {
      margin: 0;
      padding: 0;
      font-family: "Raleway", sans-serif;
    }

    /* Title style */
    #title {
      background-color: #715aa0;
      color: #ffffff;
      font-size: 24px;
      font-weight: bold;
      padding: 15px 0;
      text-align: center;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }

    /* Content style */
    #content {
      background-color: #eeeeee;
      padding: 20px;
    }

    /* Logo style */
    #logo img {
      display: block;
      margin: 0 auto;
    }

    /* Greeting style */
    .greeting {
      font-size: 18px;
      margin-bottom: 20px;
      text-align: center;
    }

    /* Message style */
    .message {
      font-size: 16px;
      margin-bottom: 20px;
    }

    /* Verification code style */
    .verification-code {
      font-size: 40px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      display: block;
      font-family: Arial, Helvetica, sans-serif;
      margin-top: 15px
    }

    /* Security note style */
    .security-note {
      font-size: 16px;
      margin-bottom: 20px;
    }

    /* Signature style */
    .signature {
      font-size: 18px;
      font-weight: bold;
      color: #707070;
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body style="background-color: #f4f4f4;font-family: 'Raleway', sans-serif;">

  <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f4f4f4">
    <tr>
      <td align="center" valign="top" style="padding: 40px 10px;">
        <!-- Container table -->
        <table cellpadding="0" cellspacing="0" border="0" width="600" bgcolor="#ffffff" style="border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
          <!-- Logo row -->
          <tr>
            <td colspan="2" align="center" style="padding: 20px 0;" id="logo">
              <img src="https://picastro.beckapps.co/public/assets/images/picastro.png" height="100" alt="Picastro Logo">
            </td>
          </tr>
          <!-- Title row -->
          <tr>
            <td colspan="2" id="title">Verification Code</td>
          </tr>
          <!-- Content row -->
          <tr>
            <td colspan="2" id="content">
              <!-- Greeting -->
              <p class="greeting"><strong>Hello {{$data['name']}},</strong></p>
              <!-- Message -->
              <p class="message">We have received a request to access your {{$data['email']}} email account through your email address. Your verification code is: <span class="verification-code">{{$data['otp']}}</span></p>
              <!-- Security Note -->
              <p class="security-note">If you didn’t request this code, it’s possible that someone else is trying to access your {{$data['email']}} account. <strong>Do not forward or give this code to anyone.</strong></p>
              <!-- Closing Note -->
              <p class="signature">Your Sincerely,</p>
              <!-- Team Name -->
              <p class="signature">Picastro Team</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

</body>

</html>