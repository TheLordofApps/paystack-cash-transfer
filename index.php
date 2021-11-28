 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paystack  Identity Verifier</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <h1>Verify Identity</h1>
   <hr>
   <div class="container">
      <form action="verify_identity.php" method="post">
        <label>Account Number</label>
        <input type="text" name="account_number" placeholder="Enter your account number here...">
        <label>Bank Code</label>
        <input type="text" name="bank_code" placeholder="Enter your bank code here...">
        <input type="submit" name="identify" value="Verify Account">
      </form>
   </div> 
</body>
</html>