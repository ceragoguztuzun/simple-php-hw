<?php
    // display applied companies
    function displayCompaniesTable($conn, $sid) {
        $qry = "SELECT cid, cname, quota FROM student NATURAL JOIN company NATURAL JOIN apply WHERE sid = '$sid'";
        $result = mysqli_query($conn, $qry);
        if (!$result) {
            printf("mysqli_query failure:" + mysqli_error($conn));
            exit();
        }

        $result_rows = mysqli_num_rows($result);
        
        if($result_rows > 0){
            // form table 
            echo "<div id=\"centerwrapper\">";
            echo "<table style=\"text-align: center;\"><tr><th style=\"padding-right:30px\">Company ID</th><th style=\"padding-right:30px\">Company Name</th><th style=\"padding-right:30px\">Quota</th><th> </th></tr>"; //TODO: center align
            while($row = mysqli_fetch_array($result)) {
                echo "<tr><td style=\"padding-right:30px\">".$row['cid']."</td><td style=\"padding-right:30px\">".$row['cname']."</td><td style=\"padding-right:30px\">".$row['quota']."</td>";
                // cancel button
                // <a class="btn btn-primary active" href="add.php">Apply For New Internship</a>
                echo "<td><form action=\"\" METHOD=\"POST\">
                    <button type=\"submit\" 
                    class=\"btn btn-primary active\" 
                    name = \"cidToCancel\" 
                    style=\"background-color:red;\"
                    value =" . $row['cid'] . ">
                    Cancel
                    </button></form></td></tr>";
            }
            echo "</table></div>";
        }
        // student didnt apply to any companies
        else{
            echo "empty table";
        }
    }

    // cancels selected internship and increases quota by 1
    function cancelInternship($conn, $cid, $sid) {
        echo "in XXXXXXX";
        $qry = "DELETE FROM apply WHERE cid = '$cid' and sid = '$sid'";
        $result = mysqli_query($conn, $qry);
        if (!$result) {
            printf("An error occured while canceling the internship!" + mysqli_error($conn));
            exit();
        }
        $qry = "UPDATE company SET quota = quota + 1 WHERE cid='$cid'";
        $result = mysqli_query($conn, $qry);
        if (!$result) {
            printf("An error occured while increasing the quota of cancelled internship!" + mysqli_error($conn));
            exit();
        }
        // success
        echo "<script LANGUAGE='JavaScript'> window.alert(' $cid Internship is cancelled!'); </script>";
        // refresh page
        echo "<script LANGUAGE='JavaScript'> window.location.href = 'welcome.php' </script>";

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Welcome Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        #centerwrapper { 
            text-align: center;
        }
        #rightwrapper { 
            text-align: right;
            margin-right: 150px;
            margin-top: 50px;
        }
        th {
            text-align: center;
        }
        td {
            text-align: center;
        }
        table {
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>


<div id="rightwrapper">
    <a class="btn btn-primary active" href="index.php">Go Back</a>            
    <a class="btn btn-primary active" href="logout.php">Log Out</a>
</div>

<div id="centerwrapper">
    <?php
        include_once 'conn.php';
        session_start();
        echo "<h2 class=\"page-header\" style=\"font-weight: bold;\">Summer Intenship Applications of " . $_SESSION['sname']  . " (" . $_SESSION['sid'] . ")" . "</h2>";
    
        $sid = $_SESSION["sid"];
        displayCompaniesTable($conn, $sid);

        if( isset( $_POST['apply'])) {
            // check if student is applied to up to 3 companies MAX
            $qry = "SELECT cid, cname, quota FROM student NATURAL JOIN company NATURAL JOIN apply WHERE sid = '$sid'";
            $result = mysqli_query($conn, $qry);
            if (!$result) {
                printf("mysqli_query failure:" + mysqli_error($conn));
                exit();
            }

            $result_rows = mysqli_num_rows($result);
            
            if($result_rows == 3){
                echo "<script LANGUAGE='JavaScript'> window.alert('Student can apply up to 3 Companies!'); </script>";
                echo "<script LANGUAGE='JavaScript'> window.location.href = 'welcome.php' </script>";
            }
            // navigate to add.php
            else {
                echo "<script LANGUAGE='JavaScript'> window.location.href = 'add.php' </script>";
            }
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // cancel internship
            echo "cancel post req:::" . $_POST['cidToCancel'] . " ";
            cancelInternship($conn, $_POST['cidToCancel'], $sid);
        }
    ?>

    <form id="applyToInternshipForm" action="" method="post">
        <br><input type="submit" name="apply" class="btn btn-primary active" value="Apply For New Internship">
    </form>
</div>


</body>
</html>

