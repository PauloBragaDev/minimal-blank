<!doctype html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?= $title ?? CONF_SITE_NAME; ?></title>
    <style>
        body {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        table {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .content {
            font-size: 16px;
            margin: 0;
        }

        .content p {
            margin: 15px 0;
            line-height: 1.6;
        }

        .content h2 {
            margin-top: 0;
        }

        .content a {
            color: #667eea;
            text-decoration: none;
        }

        .content a:hover {
            text-decoration: underline;
        }

        .footer {
            font-size: 14px;
            color: #888888;
            font-style: italic;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>
    <?= $this->section("styles"); ?>
</head>
<body style="padding: 20px 0;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
    <tr>
        <td style="padding: 20px;">
            <?= $this->section("content"); ?>
        </td>
    </tr>
</table>
</body>
</html>
