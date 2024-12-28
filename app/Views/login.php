<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #ffe6e6; /* Background merah muda */
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-container {
      width: 100%;
      max-width: 400px;
      padding: 20px;
      background-color: #ffffff; /* Warna putih */
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    .login-container h1 {
      color: #d32f2f; /* Warna merah gelap */
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }
    .btn-red {
      background-color: #d32f2f; /* Tombol merah */
      color: #ffffff;
      border: none;
    }
    .btn-red:hover {
      background-color: #b71c1c;
    }
    .form-control:focus {
      border-color: #d32f2f;
      box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
    }
    .text-link {
      color: #d32f2f;
    }
    .text-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
  <?php if (isset($_GET['success'])) : ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_GET['success']) ?>
    </div>
<?php endif; ?>

  <?php include 'template/alertMessage.php' ?>
    <h1>Login</h1>
    <form action="login" method="post">
      <div class="mb-3">
        <label for="floatingInput">Nama Pengguna</label>
        <input type="text" name="username" class="form-control" id="floatingInput">
      </div>
      <div class="mb-3">
        <label for="floatingPassword">Kata Sandi</label>
      <input type="password" name="password" class="form-control" id="floatingPassword">
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me" value="1">
        <label class="form-check-label" for="rememberMe">Remember Me</label>
      </div>
      <button type="submit" class="btn btn-red w-100">Login</button>
      <div class="text-center mt-3">
      </div>
    </form>
  </div>
  <script src="template.js"></script>
</body>
</html>
