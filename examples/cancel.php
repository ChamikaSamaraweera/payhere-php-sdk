<?php
/**
 * PayHere Payment Cancel Page
 * 
 * This page is shown when the customer cancels the payment
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .message {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .button {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .button-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .button-secondary {
            background: #f8f9fa;
            color: #333;
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">❌</div>
        <h1>Payment Cancelled</h1>
        <p class="message">
            Your payment was cancelled. No charges were made to your account.
            You can try again or return to the home page.
        </p>
        
        <div class="buttons">
            <a href="checkout.php" class="button button-primary">Try Again</a>
            <a href="/" class="button button-secondary">Go Home</a>
        </div>
    </div>
</body>
</html>
