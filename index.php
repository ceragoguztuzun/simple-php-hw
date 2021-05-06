<?php
    include_once 'conn.php';
    session_start();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        #centerwrapper { 
            text-align: center;
        }
    </style>
</head>
<body>

<?php

    if( isset( $_POST['login'])) {
        // check inputs
        if( $_POST['username'] == '' or $_POST['password'] == '') {
            echo "<script type='text/javascript'>alert('Please fill all fields!');</script>";
        }
        else{
            // check if inputs are in the db
            $username = $_POST['username'];
            $password = $_POST['password'];
            $qry = "SELECT sname, sid FROM student WHERE sname = '$username' and sid = '$password'";
            $result = mysqli_query($conn, $qry);
            if (!$result) {
                printf("mysqli_query failure:" + mysqli_error($conn));
                exit();
            }
            $result_rows = mysqli_num_rows($result);
            
            // inputs are correct, start sesh
            if($result_rows == 1){
                session_start();
                
                // init session variables
                $_SESSION['sname'] = $username;
                $_SESSION['sid'] = $password;

                header("location: welcome.php");
            }
            // inputs are wrong
            else{
                echo "<script type='text/javascript'>alert('Invalid Username or Password.');</script>";
            }
        }
    }   
    
?>

<div class="container">
    <div id="centerwrapper">
            <br><br>
            <h2>Login to the Internship Page</h2>
            <p>Please enter the username and password.</p>
            <form id="loginForm" action="" method="post">
                Username<br><input type="text" name="username" size="50" id="username" placeholder="John"><br>
                Password<br><input type="password" name="password" size="50" id="password" placeholder="2100000x"><br> 
                <br><input type="submit" name="login" class="btn btn-primary active" value="Login">
            </form>
    </div>
</div>

</script>
</body>
</html>