<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>{{ env('SITE_TITLE') }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<table cellpadding="0" cellspacing="0"
       style="width:100%; max-width:640px; margin:0 auto; border:0; font-family: Calibri, Arial; font-size:13px; color:#3e3e40; text-align:left;">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" style=" width:100%; background-color:#242424; text-align:center;">
                <tr>
                    <td style=" width:7%;"></td>
                    <td style="padding:15px 0 10px 0;"><a href="{{url('/')}}">
                            <!-- <img src="<?php echo asset('public/assets');?>/images/email/logo.png" alt=""/>-->
                            <img src="http://via.placeholder.com/250x50?text=Company+Logo" alt="" />
                        </a></td>
                    <td style=" width:7%;"></td>
                </tr>
            </table>
        </td>
    <tr>
    <tr>
        <td align="center">
            <table cellpadding="0" cellspacing="0"
                   style=" width:100%; margin:45px 0; background-color:#fff; font-size:16px;">
                <tr>
                    <td style=" width:7%;"></td>
                    <td>
                        <table cellpadding="0" cellspacing="0" style="width:100%;">
                            {!! $data['message'] !!}
                        </table>
                    </td>
                    <td style=" width:7%;"></td>
                </tr>
            </table>
        </td>
    <tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0"
                   style=" width:100%; padding:10px 0; background-color:#252525; color: #fff;">
                <tr>
                    <td style=" width:7%;"></td>
                    <td>
                        <table cellpadding="0" cellspacing="0" style=" width:100%;">
                            <tr>
                                <td valign="middle">Copyright © <?php echo date('Y'); ?> {{ env('COPYRIGHT_TEXT') }}</td>
                                <td valign="middle" style=" width:40%; padding-top:5px;">
                                    <a href="#"><img
                                                src="http://via.placeholder.com/50x50?text=Logo" alt=""/></a>
                                    <a href="#"><img
                                                src="http://via.placeholder.com/50x50?text=Logo" alt=""/></a>
                                    <a href="#"><img
                                                src="http://via.placeholder.com/50x50?text=Logo" alt=""/></a>
                                    <a href="#"><img
                                                src="http://via.placeholder.com/50x50?text=Logo" alt=""/></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style=" width:7%;"></td>
                </tr>
            </table>
        </td>
    <tr>
</table>
</body>
</html>


<!-- <html>
    <body>

        <h1>HEADER</h1>
        {!!$data['message'] !!}

        <h1>FOOTER</h1>
    </body>
</html> -->