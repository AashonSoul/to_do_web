<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO DO LIST</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="media.css" media="(max-width: 1158px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<?php 
include '_dumps/db_connect.php';
session_start();
?>

    <!-- SIGN UP popup -->
    <?php
    if(!isset($_SESSION['loggedin'])){
        echo "
        <form class='popup' id='signup_popup' action='index.php' method='post'>
            <div class='cross_icon'>
                <i id='signup_cross_btn' class='fa-sharp fa-solid fa-circle-xmark'></i>
            </div>
            <div class='popup_contents'>
                <h1>Sign up to our website</h1>
                <div class='inputs'>
                    <label for='username'>Enter Username:</label>
                    <input type='text' name='username' id='username'>
                </div>

                <div class='inputs'>
                    <label for='pass'>Enter Password:</label>
                    <input type='text' name='pass' id='pass'>
                </div>

                <div class='inputs'>
                    <label for='c_pass'>Confirm Password:</label>
                    <input type='text' name='c_pass' id='c_pass'>
                </div>
                
                <div class='popup_btn'>
                    <button>Sign Up</button>
                </div>
                <h5>Already have an account? <a class='click_here' onclick='click_here_login()'> Click here to login in </a></h5>
            </div>
        </form>
        ";
    }
    
    ?>
    

    <!-- LOGIN IN popup -->
    <form class="popup" id="login_popup" action="index.php" method="post">
        <div class="cross_icon">
            <i id="login_cross_btn" class="fa-sharp fa-solid fa-circle-xmark"></i>
        </div>
        <div class="popup_contents">
            <h1>Login to our website</h1>
            <div class="inputs">
                <label for="login_user">Enter Username:</label>
                <input type="text" name="login_user" id="login_user">
            </div>

            <div class="inputs">
                <label for="login_pass">Enter Password:</label>
                <input type="text" name="login_pass" id="login_pass">
            </div>
            
            <div class="popup_btn">
                <button >Login in</button>
            </div>
            <h5>Don't have an account? <a class='click_here' onclick='click_here_signup()' href="#"> Click here to Sign up</a></h5>
        </div>
    </form>

    <!-- Submitting the login form -->
    <?php

    $login_success = false;
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(isset($_POST["login_user"]) && isset($_POST["login_pass"])){

            $login_user = $_POST["login_user"];
            $login_pass = $_POST["login_pass"];
            
            $sql = "SELECT * FROM `users` WHERE username = '$login_user'";
            $result = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);
            if($num == 1){
                while($row=mysqli_fetch_assoc($result)){
                    if(password_verify($login_pass, $row['password'])){
                        $login_success = true;
                        // session_start();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $login_user;
                        $_SESSION['loginid'] = $row['sno'];
                    }
                    else{
                        $show_error = "Invalid Credentials";
                        echo $show_error;
                    }
                }
            }
            else{
                $show_error = "Invalid Credentials";
                echo $show_error;
            }
        }
    }

    ?>

    <!-- Submitting the sign up form -->
    <?php
    $signup_success = false;
    $pass_no_match = false;
    $same_user = false;
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['username']) && isset($_POST['pass']) && isset($_POST['c_pass'])){
            $username = $_POST['username'];
            $pass = $_POST['pass'];
            $c_pass = $_POST['c_pass'];

            $sql = "SELECT username FROM `users` WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $nums_of_user = mysqli_num_rows($result);

            if($pass===$c_pass){
                if($nums_of_user ===0){
                    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO `users` (`username`, `password`) VALUES ('$username', '$pass_hash')";
                    $result = mysqli_query($conn, $sql);
                    if($result){
                        $signup_success = true;
                    }
                }
                else{
                    $same_user = true;
                }
            }
            else{
                $pass_no_match = true;
            }
        }
    }
    
    ?>

    <?php
    $title_empty = false;
    $desc_empty = false;
    $insertion = false;
    $deletion = false;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['title']) && isset($_POST['desc'])){
            $title = $_POST['title'];
            $desc = $_POST['desc'];
            // session_start();
            if(isset($_SESSION['loggedin'])){
                // Inserting into the db
                $login_id = $_SESSION['loginid'];
                $sql = "INSERT INTO `notes` (`title`, `content`, `user_id`) VALUES ('$title', '$desc', '$login_id')";
                if(empty($title)){
                    $title_empty = true;
                }
                if(empty($desc)){
                    $desc_empty = true;
                }
                else if($title_empty === false && $desc_empty === false){
                    $result = mysqli_query($conn, $sql);
                    $insertion = true;
                }
            }
            else{
                echo "Please login to insert you lill tasks";
            }

        }
    }

    // Deleting from the db
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "DELETE FROM notes WHERE sno = $id";
        $result = mysqli_query($conn, $sql);
        if($result){
            $deletion = true;
        }
    }
    ?>

    <!-- MORE popup -->
    <?php 
    if(isset($_GET['more_id'])){
        $more_id = $_GET['more_id'];
        $sql = "SELECT * FROM `notes` where sno=$more_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $title = $row['title'];
        $desc = $row['content'];
        echo "
        <div class='more_popup'>
            <div class='cross_icon'>
                <i id='more_cross_btn' class='fa-sharp fa-solid fa-circle-xmark'></i>
            </div>
            <div class='more_div_contents'>
                <h1>$title</h1>
                <p>$desc</p>
            </div>
        </div>";
    }
    ?>


    <header>
        <div id="div1">
            <div id="header_side_menu" class="d_none">
                <?php 
                if(isset($_SESSION['loggedin'])){
                    echo "These are your added notes: <br>";
                    $login_id = $_SESSION['loginid'];
                    $sql = "SELECT title FROM `notes` WHERE user_id = '$login_id' LIMIT 28";
                    $result = mysqli_query($conn, $sql);
                    $sno = 1;
                    while($row = mysqli_fetch_assoc($result)){
                        $title = $row['title'];
                        echo "<p class='note_title'>$sno $title</p>";
                        $sno++;
                    }
                }
                else{
                    echo "Login to see the list of your added notes";
                }
                
                ?>
            </div>
        </div>

        <div id="div2">
            <div id="navbar">
                <nav id="header_nav">
                    <ul>
                        <li class="icon"><i class="fa-solid fa-bars" id="menu"></i></li>
                        <a href='index.php'><li class="li">HOME</li></a>
                        <a href='index.php' id="login_in_btn"><li class="li sign_log">Login</li></a>
                        <a href='index.php' id="sign_up_btn"><li class="li sign_log">Signup</li></a>
                        <?php 
                        if(isset($_SESSION['loggedin'])){
                            echo "
                            <a href='index.php?logout=true' id='log_out_btn'><li class='li sign_log'>Logout</li></a>
                            ";
                        }
                        if(isset($_GET['logout'])){
                            $logout = $_GET['logout'];
                                echo "logout is set true here";
                                session_start();
                                if(isset($_SESSION['username'])){
                                    session_destroy();
                                    header('location: index.php');
                                }
                                else{
                                    header('location: index.php');
                                }
                        }
                        function logout(){
                            echo "LOGGING OUT";
                        }
                        
                        ?>
                        <span>
                            <?php
                            // session_start();
                            if(isset($_SESSION['loggedin'])){
                                echo "Logged in as ".$_SESSION['username'];
                            }
                            ?>
                        </span>
                    </ul>
                </nav>
            </div>
            

            <?php
            // ALL ALERTS BELOW
            if($insertion===true){
                echo "
                <div id='message' class='green_alert'>The note had been added successfully !-- <i onclick='remove_mess()' class='fa-solid fa-xmark'></i></div>
                ";
            }
            if($deletion===true){
                echo "
                <div id='message' class='green_alert'>The note had been deleted successfully !-- <i onclick='remove_mess()' class='fa-solid fa-xmark'></i></div>
                ";
            }
            if($signup_success===true){
                echo "
                <div id='message' class='green_alert'>Success! You can now login through your account-- <i onclick='remove_mess()' class='fa-solid fa-xmark'></i></div>
                ";
            }
            if($pass_no_match===true){
                echo "
                <div id='message' class='red_alert'>Passwords do not match please try again!-- <i onclick='remove_mess()' class='fa-solid fa-xmark'></i></div>
                ";
            }
            if($same_user===true){
                echo "
                <div id='message' class='red_alert'>This username already exists!-- <i onclick='remove_mess()' class='fa-solid fa-xmark'></i></div>
                ";
            }
            if(isset($_SESSION['loggedin'])){
                echo "
                <div id='message' class='green_alert'>Successfully Logged in!-- <i onclick='remove_mess()' class='fa-solid fa-xmark'></i></div>
                ";
            }
            if(isset($_SESSION['loggedin'])){
            echo "
                <div id='message' class='yellow'>NOTICE: Kingly logout before switching accounts!-- <i onclick='remove_echo()' class='fa-solid fa-xmark'></i></div>
            ";
            }

            ?>
    
            <div id="container">
                <H1>List Your Lill Tasks</H1>
                <div class="form">
                    <form class="adding_notes_sec" method="POST" action="/to_do_web/index.php">
                        <div class="inputs">
                            <label for="title">Note Title:</label>
                            <input type="text" name="title" id="title" placeholder="Enter Your Note Title">
                            <?php
                            if($title_empty === true){
                                echo "*title is required";
                            }
                            ?>
                        </div>
                        <div class="inputs">
                            <label for="desc">Note Description:</label>
                            <input type="text" name="desc" id="desc" placeholder="Enter Your Note(Try to keep it short and crisp)">
                            <?php
                            if($desc_empty === true){
                                echo "*description is required";
                            }
                            ?>
                        </div>
                        <div class="btn">
                            <button class="buttons">ADD NOTE</button>
                        </div>
                    </form>
                    <hr>

                    <div class="added_notes_sec">

                    <?php
                    if(isset($_SESSION['loggedin'])){
                        // Fetching from the db
                        $login_id = $_SESSION['loginid'];
                        $sql = "SELECT * FROM `notes` WHERE user_id = '$login_id'";
                        $result = mysqli_query($conn, $sql);
                        $num = mysqli_num_rows($result);
                        $sno=1;

                        $limit = 4;
                        if(isset($_GET['page_no'])){
                            $page_number = $_GET['page_no'];
                        }
                        else{
                            $page_number = 1;
                        }
                        $initial_page = ($page_number-1) * $limit; 
                        $total_rows = mysqli_num_rows($result);
                        $total_pages = ceil($total_rows/$limit);
                        $get_query = "SELECT *FROM notes WHERE user_id = '$login_id' LIMIT " . $initial_page . ',' . $limit; 
                        $result2 = mysqli_query($conn, $get_query);

                        if($num>0){
                            while($row = mysqli_fetch_assoc($result2)){
                                $dis_title = $row['title'];
                                $dis_desc = $row['content'];
                                $id=$row['sno'];
                                echo '<div class="notes">
                                        <h3>'. $sno. ". " . $dis_title .'</h3>
                                        <p>'. substr($dis_desc,0,100). '<a class="more_link" href="index.php?more_id='. $id .'">...more</a>' .'</p>
                                        <a href="index.php?id='. $id .'"><button class="buttons">Delete Note</button></a>
                                    </div>';
                                $sno++;
                            }
                        }
                        else{
                            echo "
                            <div class='notifications'>
                                 Add notes quickly to see them here!--
                            </div>";
                        }
                    }
                    else{
                        echo "
                        <div class='notifications'>
                            Login and add notes to see them here!--
                        </div>";
                    }
                    
                    ?>

                    </div>
                    <?php
                    if(isset($_SESSION['loggedin'])){
                        $no_of_pages = ceil($num/4);
                        if($num>4){
                        echo "
                        <div id='pagination'>
                                <i class='fa-solid fa-angles-left bg' id='previous'></i>";

                                $pagination_serial = 1;
                                while($no_of_pages){
                                    echo "<button class='nums bg'><a href='index.php?page_no=".$pagination_serial."'>". $pagination_serial ."</a></button>";
                                    $no_of_pages--;
                                    $pagination_serial++;
                                }
                                
                                echo "<i class='fa-solid fa-angles-right bg' id='next'><a href='index.php?page_no=2'></a></i>";
                        "</div>
                        ";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </header>


    <footer class="<?php if(!isset($_SESSION['loggedin'])){echo 'footer_position';}?>">
        © 1996-2023, LILL LIST OF YOUR WORKS TO DO, Inc. or its affiliates
    </footer>
    
    
    <script src="scripting.js"></script>
</body>
</html>