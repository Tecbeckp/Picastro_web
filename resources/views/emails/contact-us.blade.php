<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="dark" data-bs-theme="dark" data-theme-colors="default">


<head>

    <meta charset="utf-8" />
    <title>{{ $details['name'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    @include('includes.style')


</head>

<body>

    <div class="container-fluid">
        <!-- Forgot Password Email -->
        <div class="row">

            <div class="col-12 mt-3">
                <table class="body-wrap"
                    style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: transparent; margin: 0;">
                    <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <td style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                            valign="top"></td>
                        <td class="container" width="600"
                            style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;"
                            valign="top">
                            <div class="content"
                                style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                                <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action"
                                    itemscope itemtype="http://schema.org/ConfirmAction"
                                    style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">

                                    <tr style="font-family: 'Roboto', sans-serif; font-size: 14px; margin: 0;">
                                        <td class="content-wrap"
                                            style="font-family: 'Roboto', sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0;padding: 30px; box-shadow: 0 3px 15px rgba(30,32,37,.06); ;border-radius: 7px; background-color: #000;"
                                            valign="top">
                                            <meta itemprop="name" content="Confirm Email"
                                                style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                                            <table width="100%" cellpadding="0" cellspacing="0"
                                                style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <tr
                                                    style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block"
                                                        style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                        valign="top">
                                                        <div style="text-align: center;margin-bottom: 15px;">
                                                            <img src="https://picastro.co.uk/public/assets/images/PicastroLogo.png"
                                                                alt="" height="64">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr
                                                    style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block"
                                                        style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                        valign="top">
                                                        <div style="text-align: center;">
                                                            <i data-feather="lock"
                                                                style="color: #0ab39c;fill: rgba(10,179,156,.16); height: 30px; width: 30px;"></i>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr
                                                    style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block"
                                                        style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 24px; vertical-align: top; margin: 0; padding: 0 0 10px;  text-align: center;"
                                                        valign="top">
                                                        <h4
                                                            style="font-family: 'Roboto', sans-serif; margin-bottom: 0px;font-weight: 500; line-height: 1.5;">
                                                            Contact Us</h5>
                                                    </td>
                                                </tr>
                                                <tr
                                                    style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block"
                                                        style="font-family: 'Roboto', sans-serif; color: #878a99; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 0 0 12px; text-align: center;"
                                                        valign="top">
                                                        <div class="content">
                                                            <p><strong>Name:</strong> {{ $details['name'] }}</p>
                                                            <p><strong>Email:</strong> {{ $details['email'] }}</p>
                                                            <p><strong>Message:</strong></p>
                                                            <p>{{ $details['message'] }}</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
                <!-- end table -->
            </div>
        </div>
        <!-- end row -->

        <!--end row-->

    </div>
    <!-- container-fluid -->


    @include('includes.script')
</body>

</html>
