<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $language === 'ka' ? 'მოგესალმებით მომთაბარეში!' : 'Welcome to Momtabare!' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .content {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .highlight {
            color: #2563eb;
            font-weight: 600;
        }
        .cta-button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        .features {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .feature-item {
            margin: 10px 0;
            padding-left: 20px;
            position: relative;
        }
        .feature-item:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">მომთაბარე</div>
            <h1 class="title">
                @if($language === 'ka')
                    მოგესალმებით, {{ $userName }}!
                @else
                    Welcome, {{ $userName }}!
                @endif
            </h1>
        </div>

        <div class="content">
            @if($language === 'ka')
                <p>გმადლობთ მომთაბარეში რეგისტრაციისთვის! ჩვენ ძალიან გვიხარია, რომ შემოგვიერთდით.</p>
                
                <p>მომთაბარე არის <span class="highlight">ონლაინ პლატფორმა</span>, სადაც შეგიძლიათ:</p>
                
                <div class="features">
                    <div class="feature-item">იქირავოთ სპორტული აღჭურვილობა</div>
                    <div class="feature-item">გამოიმუშავოთ დამატებითი შემოსავალი</div>
                    <div class="feature-item">მარტივად მართოთ თქვენი ბიზნესი</div>
                    <div class="feature-item">დაუკავშირდეთ სხვა მომხმარებლებს</div>
                </div>

                <p>თქვენი ანგარიში მზადაა გამოსაყენებლად. დაიწყეთ თქვენი მოგზაურობა ჩვენთან ერთად!</p>
                
                <div style="text-align: center;">
                    <a href="{{ config('app.frontend_url') }}/login" class="cta-button">შესვლა ანგარიშში</a>
                </div>
            @else
                <p>Thank you for registering with Momtabare! We're excited to have you join our community.</p>
                
                <p>Momtabare is an <span class="highlight">online platform</span> where you can:</p>
                
                <div class="features">
                    <div class="feature-item">Rent sports equipment easily</div>
                    <div class="feature-item">Generate additional income</div>
                    <div class="feature-item">Manage your business effortlessly</div>
                    <div class="feature-item">Connect with other users</div>
                </div>

                <p>Your account is ready to use. Start your journey with us today!</p>
                
                <div style="text-align: center;">
                    <a href="{{ config('app.frontend_url') }}/login" class="cta-button">Login to Your Account</a>
                </div>
            @endif
        </div>

        <div class="footer">
            @if($language === 'ka')
                <p>თუ გაქვთ კითხვები, გთხოვთ დაგვიკავშირდეთ: <a href="mailto:support@momtabare.com">support@momtabare.com</a></p>
                <p>&copy; {{ date('Y') }} მომთაბარე. ყველა უფლება დაცულია.</p>
            @else
                <p>If you have any questions, please contact us at: <a href="mailto:support@momtabare.com">support@momtabare.com</a></p>
                <p>&copy; {{ date('Y') }} Momtabare. All rights reserved.</p>
            @endif
        </div>
    </div>
</body>
</html>
