<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول من جهاز جديد</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: rtl;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .details td:first-child {
            font-weight: bold;
            color: #495057;
            width: 30%;
        }
        .button {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌱 جُذور</h1>
            <p>منصة التعلم التفاعلي</p>
        </div>
        
        <div class="content">
            <h2>مرحباً {{ $userName }}،</h2>
            
            <div class="alert">
                <strong>⚠️ تنبيه أمني:</strong> تم تسجيل الدخول إلى حسابك من جهاز جديد.
            </div>
            
            <p>إذا كنت أنت من قام بتسجيل الدخول، يمكنك تجاهل هذه الرسالة. أما إذا لم تكن أنت، يرجى اتخاذ إجراء فوري.</p>
            
            <div class="details">
                <h3>تفاصيل تسجيل الدخول:</h3>
                <table>
                    <tr>
                        <td>التاريخ والوقت:</td>
                        <td>{{ $loginTime }}</td>
                    </tr>
                    <tr>
                        <td>عنوان IP:</td>
                        <td>{{ $ipAddress }}</td>
                    </tr>
                    <tr>
                        <td>الموقع:</td>
                        <td>{{ $location }}</td>
                    </tr>
                    <tr>
                        <td>الجهاز:</td>
                        <td>{{ $device }}</td>
                    </tr>
                    <tr>
                        <td>المتصفح:</td>
                        <td>{{ $browser }}</td>
                    </tr>
                    <tr>
                        <td>النظام:</td>
                        <td>{{ $platform }}</td>
                    </tr>
                </table>
            </div>
            
            <p><strong>إذا لم تكن أنت من قام بتسجيل الدخول:</strong></p>
            <ol>
                <li>قم بتغيير كلمة المرور فوراً</li>
                <li>راجع نشاط حسابك</li>
                <li>تواصل معنا إذا لاحظت أي نشاط مشبوه</li>
            </ol>
            
            <center>
                <a href="{{ route('profile.edit') }}" class="button">تغيير كلمة المرور</a>
            </center>
        </div>
        
        <div class="footer">
            <p>هذه رسالة تلقائية من منصة جُذور. يرجى عدم الرد على هذا البريد.</p>
            <p>&copy; 2024 جُذور. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>