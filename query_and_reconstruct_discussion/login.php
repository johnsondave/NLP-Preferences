<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
  header("Location: index.php");
  return;
}

$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=mydb',
'root', 'ubcfm123');

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
  if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
    $_SESSION["error"] = "User name and password are required";
    error_log("Login fail ".$_POST['email']." $check");
    header("Location: login.php");
    return;
  } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $_SESSION["error"] = "Email must have an at-sign (@)";
    error_log("Login fail ".$_POST['email']." $check");
    header("Location: login.php");
    return;
  } else {
    unset($_SESSION["user_id"]);
    $check = hash('md5', $salt.$_POST['pass']);
    $stmt = $pdo->prepare("SELECT user_id, name from users where
      email = :em AND password = :pw");
      $stmt->execute(array(':em' =>$_POST['email'], ':pw'=> $check));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if($row !== false) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name'];
        header("Location: index.php");
        return;
      } else {
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
      }
    }
  }

  // if ( $check == $stored_hash ) {
  //   $_SESSION["account"] = $_POST["email"];
  //   $_SESSION["success"] = "Logged in.";
  //   header("Location: index.php?name=".urlencode($_POST['email']));
  //   error_log("Login success ".$_POST['email']);
  //   return;
  // } else {
  //   $_SESSION["error"] = "Incorrect password";
  //   error_log("Login fail ".$_POST['email']." $check");
  //   header("Location: login.php");
  //   return;
  // }


  // Fall through into the View
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <?php require_once "bootstrap.php"; ?>
    <title>Yifu Chen (Charles) 6d4e59d5</title>
  </head>
  <body>
    <div class="container">
      <h1>Please Log In</h1>
      <?php
      if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
      }
      ?>
      <form method="POST" action="login.php">
        <label for="email">Email</label>
        <input type="text" name="email" id="email"><br/>
        <label for="id_1723">Password</label>
        <input type="password" name="pass" id="id_1723"><br/>
        <input type="submit" onclick="return doValidate();" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
      </form>
      <p>
        For a password hint, view source and find an account and password hint
        in the HTML comments.
        <!-- Hint:
        The account is umsi@umich.edu
        The password is the three character name of the
        programming language used in this class (all lower case)
        followed by 123. -->
      </p>
      <script>
      function doValidate() {
        console.log('Validating...');
        try {
          addr = document.getElementById('email').value;
          pw = document.getElementById('id_1723').value;
          console.log("Validating addr="+addr+" pw="+pw);
          if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
          }
          if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
          }
          return true;
        } catch(e) {
          return false;
        }
        return false;
      }
    </script>

  </div>
</body>
<!-- <body>

<div class="container">
<h1>Please Log In To Check Automobiles</h1>
>


<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>

<!Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
