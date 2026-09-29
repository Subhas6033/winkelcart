<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Page Not Found</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f7fb; color: #1f2937; }
        .wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card { max-width: 620px; width: 100%; background: #fff; border-radius: 14px; box-shadow: 0 8px 24px rgba(0,0,0,.08); padding: 36px; text-align: center; }
        h1 { margin: 0 0 8px; font-size: 42px; color: #1565c0; }
        p { margin: 0 0 20px; color: #4b5563; }
        a { display: inline-block; padding: 10px 18px; border-radius: 8px; text-decoration: none; background: #1565c0; color: #fff; font-weight: 600; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>404</h1>
        <p>The page you are looking for is not available.</p>
        <a href="{{ url('/') }}">Back To Home</a>
    </div>
</div>
</body>
</html>
