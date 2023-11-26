<!-- Submitting the login form -->
<?php

$login = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){

    include '_dumps/db_connect.php';
    $login_user = $_POST["login_user"];
    $login_pass = $_POST["login_pass"];
    
    // $sql = "SELECT * from user where username = '$username' AND password = '$password'";
    $sql = "SELECT * FROM `users` WHERE 'username' = '$login_user'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if($num == 1){
        while($row=mysqli_fetch_assoc($result)){
            if(password_verify($login_pass, $row['password'])){
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("location: index.php");
                echo "successfully logged in";
            }
            else{
                $show_error = "Invalid Credentials";
            }
        }
    }
    else{
        $show_error = "Invalid Credentials";
    }
}

?>