<?php
 include 'include.php';


 //determine if they need to see the form or not
 if (!$_POST) {
    $source = 'signup page';
    if(isset($_GET['source'])){
       $source = $_GET['source'];
   }
     //they need to see the form, so create form block
    $display_block = <<<END_OF_BLOCK
    <form method="POST" action="$_SERVER[PHP_SELF]">     
    <p><label for="email">enter your email address:</label><br/>
    <input type="email" id="email" name="email"
           size="40" maxlength="150" /></p>
    <button type="submit" name="submit" value="submit">Submit</button>
    <input type='hidden' name='source' value=$source />
    </form>
  END_OF_BLOCK;
 // echo ($source);
  } else if (($_POST)) {
      //trying to subscribe; validate email address
      if ($_POST['email'] == "") {
          header("Location: index.php");
          exit;
      } else {
          //connect to database
          doDB();

          //check that email is in list
          emailChecker($_POST['email']);
          $source = $_POST['source'];

       //get number of results and do action
          if (mysqli_num_rows($check_res) < 1) {
              //free result
              mysqli_free_result($check_res);
                
              //add record
              $add_sql = "INSERT INTO emails (email, source)
                         VALUES('".$safe_email."', '$source')";
              $add_res = mysqli_query($mysqli, $add_sql)
                         or die(mysqli_error($mysqli));
              $display_block = "<p>Thanks for signing up!</p>";

              //close connection to MySQL
              mysqli_close($mysqli);
          } else {
              //print failure message
              $display_block = "<p>You're already subscribed!</p>";
          }
      }
  } 
  ?>
  <!DOCTYPE html>
  <html>
  <head>
  <title>Subscribe to the Mailing List</title>
  <link rel="stylesheet" href="/style.css">
  </head>
  <body>
    <div class="form-wrapper">
 <h4>subscribe to the mailing list</h4>
 <?php echo "$display_block"; ?>
 </div>
 </body>
 </html>