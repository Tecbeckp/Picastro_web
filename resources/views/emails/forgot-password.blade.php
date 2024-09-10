<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <style>
        *
        {
            font-family: 'Open Sans', sans-serif;
        }
        body
        {
            margin: 0;
        }
        table
        {
            border-collapse: collapse;
            width: 100%;
        }
        .main
        {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .inner-section
        {
            width: 85%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .table-border tbody tr th
        {
            border: 1px solid black;
            text-align: left;
            padding: 4px 8px;
            width: 25%;
        }
        .table-border tbody tr
        {
            border: 1px solid black;
        }
        .table-border tbody tr td
        {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
            width: 75%;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="inner-section">
        <h4>OTP code</h4>
        <table class="table-border">
            <tbody>
            <tr>
                <th>
                    OTP
                </th>
                <td sty>
                    {{ $details['body'] }}
                </td>
            </tr>
            </tbody>
        </table>
        <p>This email is for sending only, so please do not reply.</p>
    </div>
</div>
</body>
</html>