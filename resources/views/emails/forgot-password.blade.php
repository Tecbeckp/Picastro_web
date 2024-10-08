<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="dark" data-bs-theme="dark" data-theme-colors="default">


<head>

    <meta charset="utf-8" />
    <title>Picastro</title>
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
                                                            <img src="https://picastro.co.uk/public/assets/images/PicastroLogo.png" alt=""
                                                                height="64">
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
                                                            Verification Code</h5>
                                                    </td>
                                                </tr>
                                                <tr
                                                    style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block"
                                                        style="font-family: 'Roboto', sans-serif; color: #878a99; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 0 0 12px; text-align: center;"
                                                        valign="top">
                                                        <p style="margin-bottom: 13px; line-height: 1.5;">We have
                                                            received a request to access your email <a href="mailto:{{$details['email']}}"
                                                                style="font-weight: 500px;">{{$details['email']}}</a>
                                                            account through your email address. Your verification code
                                                            is</p>
                                                    </td>
                                                </tr>
                                                <tr
                                                    style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block" itemprop="handler" itemscope
                                                        itemtype="http://schema.org/HttpActionHandler"
                                                        style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 22px; text-align: center;"
                                                        valign="top">
                                                        <a href="javascript:void(0)" itemprop="url"
                                                            style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: .8125rem; color: #000; text-decoration: none; font-weight: 400; text-align: center; cursor: pointer; display: inline-block; border-radius: .25rem; text-transform: capitalize; background-color: #FFC700; margin: 0; border-color: #FFC700; border-style: solid; border-width: 1px; padding: .5rem .9rem;">{{$details['otp']}}</a>
                                                    </td>
                                                </tr>

                                                <tr
                                                    style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block"
                                                        style="color: #878a99; font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0; padding-top: 5px;  text-align: center;"
                                                        valign="top">
                                                        <p style="margin-bottom: 13px; line-height: 1.5;">If you didn't
                                                            request this code then its possible that someone else is
                                                            trying to access your code. Donot forward or give this code
                                                            to anyone.</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <div style="text-align: center; margin: 28px auto 0px auto;">
                                    <h4>Need Help ?</h4>
                                    <p style="color: #878a99;">Please send feedback or bug info to <a href="mailto:support@picastroapp.com"
                                            style="font-weight: 500px;">support@picastroapp.com</a></p>

                                </div>
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
