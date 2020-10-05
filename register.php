
<?php 
require 'config.php';
require 'login_handler.php';

$conn=mysqli_connect("localhost", "root","", "social");

require 'login_handler.php';

$fname="";
$lname="";
$em="";
$em2="";
$password="";
$password2="";
$date="";

$error_array=array();

$profile_pic="";

if(isset($_POST['register_button'])){





    $fname=strip_tags($_POST['reg_fname']); // remove html tas
    $fname=str_replace('','',$fname); // remove spaces
    $fname=ucfirst(strtolower($fname));// upper case frst letter
    $_SESSION['reg_fname']=$fname;
    // first name

    $lname=strip_tags($_POST['reg_lname']); // remove html tas
    $lname=str_replace('','',$lname); // remove spaces
    $lname=ucfirst(strtolower($lname));
    $_SESSION['reg_lname']=$lname;


    $em=strip_tags($_POST['reg_email']); // remove html tas
    $em=str_replace('','',$em); // remove spaces
    $em=ucfirst(strtolower($em));
    $_SESSION['reg_email']=$em;

    $em2=strip_tags($_POST['reg_email2']); // remove html tas
    $em2=str_replace('','',$em2); // remove spaces
    $em2=ucfirst(strtolower($em2));
    $_SESSION['reg_email2']=$em2;


    $password=strip_tags($_POST['reg_password']);
    $password2=strip_tags($_POST['reg_password2']); // remove html tas
    

    $date = date("Y-m-d");

    if($em == $em2){


        if(filter_var($em,FILTER_VALIDATE_EMAIL)){

            $em= filter_var($em,FILTER_VALIDATE_EMAIL);

            $e_check=mysqli_query($conn, "SELECT email FROM social.users WHERE email='$em'");

            $num_rows=mysqli_num_rows($e_check);

            if($num_rows>0){
                array_push($error_array, "email already in use<br>");
            }
        }

        else{

            array_push($error_array, "invalid email format<br>");
        }
    }
    else{
        array_push($error_array, "emails don't match<br>");
    }


    if(strlen($fname)> 25 || strlen($fname)<2){

        array_push($error_array, "first name must be between 2 and 25 characters<br>");
    }

    if(strlen($lname)> 25 || strlen($lname)<2){

        array_push($error_array, "last name must be between 2 and 25 characters<br>");
    }

    if($password != $password2){
        array_push($error_array, "passwords do not match<br>");
    }

    else{
        if(preg_match('/[^A-Za-z0-9]/' , $password)){

            array_push($error_array, "password must only contain english characters or numbers<br>");;
    }


    if(strlen($password>30||strlen($password)<5)){

        array_push($error_array, "your passwords must be between 5 and 30 characters<br>");
    }
}

if(empty($error_array)){

    $password=md5($password); //encrypt password

    //generate username by contatonaing firstname and lname

    $username=strtolower($fname . "_".$lname);
    $check_username_query=mysqli_query($conn,"SELECT username FROM social.users WHERE username='$username'");

    $i=0;

    while(mysqli_num_rows($check_username_query) !=0){

        $i++;
        $username=$username . "_" . $i;

        $check_username_query=mysqli_query($conn,"SELECT username FROM social.users WHERE username='$username'");


    }


    $rand= rand(1,2);
    if($rand==1){
   $profile_pic="assets/images/head_deep_blue.png";
    }
   else if($rand==2){
   $profile_pic="assets/images/head_deep_blue.png";
   }


   $query=mysqli_query($conn,"INSERT INTO social.users VALUES('', '$fname', '$lname', '$em' , '$em2' ,  '$password' ,'$date','$profile_pic','0','0','no',',')");

   array_push($error_array, "<span>You're all set go ahead and login</span><br>");

   $_SESSION['reg_fname']="";
   $_SESSION['reg_lname']="";
   $_SESSION['reg_email']="";
   $_SESSION['reg_email2']="";
   
}
}




?>








<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>

<form action="register.php" method=POST>
<input type="email" name="log_email" placeholder="Email address">
<br>
<input type="password" name="log_password" placeholder="password">
<br>
<input type = "submit" name="login_button" value="Login">
<br>

</form>
  <form action="register.php" method="POST">
  <input type="text" name="reg_fname" placeholder="First Name" value = "<?php if(isset($_SESSION['reg_fname'])){

      echo $_SESSION['reg_fname'];
  }  ?>"  required>
  <br>
<?php if(in_array("first name must be between 2 and 25 characters<br>",$error_array)) echo "first name must be between 2 and 25 characters<br>"?>


  <input type="text" name="reg_lname" placeholder="Last Name" value = "<?php if(isset($_SESSION['reg_lname'])){

echo $_SESSION['reg_lname'];
}  ?>" required>
  <br>

  <?php if(in_array("last name must be between 2 and 25 characters<br>", $error_array)) echo "last name must be between 2 and 25 characters<br>" ?>
  <input type="email" name="reg_email" placeholder="Email" value = "<?php if(isset($_SESSION['reg_email'])){

echo $_SESSION['reg_email'];
}  ?>" required>
  <br>
 
  <input type="email" name="reg_email2" placeholder="Comfirm Email"value = "<?php if(isset($_SESSION['reg_email2'])){

echo $_SESSION['reg_email2'];
}  ?>"  required>
  <br>
  <?php if(in_array("emails don't match<br>",$error_array)) echo "emails don't match<br>" ;
   else if(in_array("invalid email format<br>",$error_array)) echo "invalid email format<br>" ;
   else if(in_array("email already in use",$error_array)) echo "email already in use<br>" ?>
  <input type="password" name="reg_password" placeholder="Password" required>
  <br>
  <input type="password" name="reg_password2" placeholder="Confirm Password" required>
  <br>
  <?php if(in_array("passwords do not match<br>",$error_array)) echo "passwords do not match<br>" ;
   else if(in_array("password must only contain english characters or numbers<br>",$error_array)) echo "password must only contain english characters or numbers<br>" ;
   else if(in_array("your passwords must be between 5 and 30 characters<br>",$error_array)) echo "your passwords must be between 5 and 30 characters<br>"?>
 
  <input type = "submit" name="register_button" value="Register">

  <?php if(in_array( "<span>You're all set go ahead and login</span><br>",$error_array)) echo  "<span>You're all set go ahead and login</span><br>"?> ;

  </form>
</body>
</html>



