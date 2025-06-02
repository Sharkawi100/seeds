<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة - جُذور</title>
</head>
<body style="font-family: 'Tajawal', Arial, sans-serif; direction: rtl; background-color: #f7fafc; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 32px;">جُذور</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">رسالة جديدة من نموذج الاتصال</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <h2 style="color: #2d3748; margin-bottom: 20px;">تفاصيل الرسالة</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        <strong>النوع:</strong>
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        @switch($data['type'])
                            @case('demo')
                                طلب عرض تجريبي
                                @break
                            @case('support')
                                طلب دعم فني
                                @break
                            @case('partnership')
                                طلب شراكة
                                @break
                        @endswitch
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        <strong>الاسم:</strong>
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        {{ $data['name'] }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        <strong>البريد الإلكتروني:</strong>
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        <a href="mailto:{{ $data['email'] }}" style="color: #667eea;">{{ $data['email'] }}</a>
                    </td>
                </tr>
                @if(!empty($data['school']))
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        <strong>المدرسة/المؤسسة:</strong>
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        {{ $data['school'] }}
                    </td>
                </tr>
                @endif
                @if(!empty($data['phone']))
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        <strong>رقم الهاتف:</strong>
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                        {{ $data['phone'] }}
                    </td>
                </tr>
                @endif
            </table>
            
            <div style="margin-top: 30px; padding: 20px; background-color: #f7fafc; border-radius: 8px;">
                <h3 style="color: #2d3748; margin-top: 0;">الرسالة:</h3>
                <p style="color: #4a5568; line-height: 1.6; white-space: pre-wrap;">{{ $data['message'] }}</p>
            </div>
            
            <div style="margin-top: 30px; text-align: center;">
                <a href="mailto:{{ $data['email'] }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                    الرد على الرسالة
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #f7fafc; padding: 20px; text-align: center; color: #718096; font-size: 14px;">
            <p style="margin: 0;">تم الإرسال من منصة جُذور التعليمية</p>
            <p style="margin: 5px 0 0 0;">{{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>