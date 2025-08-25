<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $language === 'ka' ? 'კეთილი იყოს თქვენი მობრძანება!' : 'Welcome to Momtabare!' }}</title>
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
            color: #10b981;
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
            color: #10b981;
            font-weight: 600;
        }
        .cta-button {
            display: inline-block;
            background-color: #10b981;
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
        .success-badge {
            background-color: #d1fae5;
            color: #065f46;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin: 20px 0;
            font-weight: 600;
        }
        .next-steps {
            background-color: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0ea5e9;
        }
        .step {
            margin: 15px 0;
            padding-left: 25px;
            position: relative;
        }
        .step:before {
            content: counter(step-counter);
            counter-increment: step-counter;
            position: absolute;
            left: 0;
            top: 0;
            background-color: #0ea5e9;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        .steps-container {
            counter-reset: step-counter;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">მომთაბარე</div>
            <h1 class="title">
                @if($language === 'ka')
                    🎉 კეთილი იყოს თქვენი მობრძანება, {{ $userName }}!
                @else
                    🎉 Welcome to Momtabare, {{ $userName }}!
                @endif
            </h1>
            <div class="success-badge">
                @if($language === 'ka')
                    ✅ რეგისტრაცია წარმატებით დასრულდა
                @else
                    ✅ Registration Successfully Completed
                @endif
            </div>
        </div>

        <div class="content">
            @if($language === 'ka')
                <p>გილოცავთ! თქვენი რეგისტრაცია მომთაბარეში წარმატებით დასრულდა. ახლა შეგიძლიათ სრულად ისარგებლოთ ჩვენი პლატფორმის ყველა შესაძლებლობით.</p>
                
                <div class="next-steps">
                    <h3>შემდეგი ნაბიჯები:</h3>
                    <div class="steps-container">
                        <div class="step">შეავსეთ თქვენი პროფილი სრული ინფორმაციით</div>
                        <div class="step">დაამატეთ თქვენი სპორტული აღჭურვილობა</div>
                        <div class="step">დაიწყეთ იჯარის მიცემა და შემოსავლის მიღება</div>
                        <div class="step">მოძებნეთ საჭიროებისამებრ აღჭურვილობა</div>
                    </div>
                </div>

                <p>ჩვენი გუნდი ყოველთვის მზადაა დაგეხმაროთ. თუ გაქვთ რაიმე კითხვა ან დახმარება გჭირდებათ, არ მოგერიდოთ დაგვიკავშირდეთ.</p>
                
                <div style="text-align: center;">
                    <a href="{{ config('app.frontend_url') }}/dashboard" class="cta-button">დაიწყეთ ახლავე</a>
                </div>
            @else
                <p>Congratulations! Your registration with Momtabare has been successfully completed. You can now take full advantage of all our platform features.</p>
                
                <div class="next-steps">
                    <h3>Next Steps:</h3>
                    <div class="steps-container">
                        <div class="step">Complete your profile with full information</div>
                        <div class="step">Add your sports equipment for rental</div>
                        <div class="step">Start renting out and earning income</div>
                        <div class="step">Find equipment you need to rent</div>
                    </div>
                </div>

                <p>Our team is always ready to help you. If you have any questions or need assistance, don't hesitate to contact us.</p>
                
                <div style="text-align: center;">
                    <a href="{{ config('app.frontend_url') }}/dashboard" class="cta-button">Get Started Now</a>
                </div>
            @endif
        </div>

        <div class="footer">
            @if($language === 'ka')
                <p><strong>დახმარება:</strong> <a href="mailto:support@momtabare.com">support@momtabare.com</a></p>
                <p><strong>ტელეფონი:</strong> +995 XXX XXX XXX</p>
                <p>&copy; {{ date('Y') }} მომთაბარე. ყველა უფლება დაცულია.</p>
            @else
                <p><strong>Support:</strong> <a href="mailto:support@momtabare.com">support@momtabare.com</a></p>
                <p><strong>Phone:</strong> +995 XXX XXX XXX</p>
                <p>&copy; {{ date('Y') }} Momtabare. All rights reserved.</p>
            @endif
        </div>
    </div>
</body>
</html>
