<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Register Buyer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: hsl(57 79.3% 60.5%);
      ;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
    }

    h2 {
      text-align: center;
      color: #333333;
      margin-bottom: 25px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      color: #333;
      font-weight: 500;
    }

    input,
    select {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      box-sizing: border-box;
    }

    input[type="checkbox"] {
      width: auto;
      margin-right: 8px;
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    input[type="submit"] {
      background-color: #0073e6;
      color: #fff;
      border: none;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #005bb5;
    }

    @media (max-width: 600px) {
      .container {
        padding: 25px 20px;
      }
    }
    .required-red {
      color: red;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="{{url('/')}}" class="header-logo">
      <img src="./assets/images/logo/Winkel_Shop__2_-removebg-preview.png" alt="Winkel's logo" width="200"
        height="200">
    </a>
    {{-- Show success message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h2>Register Your Business</h2>
    <form action="{{ url('buyer-store') }}" id="loan_form" name="loan_form" method="POST">
      @csrf
      <label>Name:<span class="required-red">*</span>
        <input type="text" name="name" value="{{ old('name') }}" required>
      </label>

      <label>Address:<span class="required-red">*</span>
        <input type="text" name="address" value="{{ old('address') }}" required>
      </label>
      
      <label>Phone:<span class="required-red">*</span>
        <input type="text" class="allow-only-numeric" name="mobile" maxlength="10" value="{{ old('mobile') }}" required>
      </label>

      <label>Email:<span class="required-red">*</span>
        <input type="email" name="email" value="{{ old('email') }}" required>
      </label>


      <div class="checkbox-label">
        <input type="checkbox" name="terms" value="{{ old('terms') }}" required>
        <label for="terms">I agree to the terms and conditions</label>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>      
    </form>
  </div>
  </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>  
  $('.allow-only-numeric').keyup(function() {
      var node = $(this);
      node.val(node.val().replace(/[^0-9]/g,''));
  });

</script>

</body>

</html>