<?php
    // display applied companies
    function displayCandidateCompaniesTable($conn, $sid) {
        $qry = "SELECT cid, cname, quota FROM company AS C WHERE quota > 0 and NOT EXISTS( SELECT cid FROM apply AS A WHERE A.cid = C.cid and A.sid = " . $_SESSION['sid'].")";
        $result = mysqli_query($conn, $qry);
        if (!$result) {
            printf(mysqli_error($conn));
            exit();
        }

        $result_rows = mysqli_num_rows($result);
        
        if($result_rows > 0){
            // form table
            echo "<div id=\"centerwrapper\">";
            echo "<table style=\"text-align: center;\"><tr><th style=\"padding-right:30px\">Company ID</th><th style=\"padding-right:30px\">Company Name</th><th style=\"padding-right:30px\">Quota</th><th> </th></tr>"; //TODO: center align
            while($row = mysqli_fetch_array($result)) {
                echo "<tr><td style=\"padding-right:30px\">".$row['cid']."</td><td style=\"padding-right:30px\">".$row['cname']."</td><td style=\"padding-right:30px\">".$row['quota']."</td>";
            }
            echo "</table></div>";
        }
        // student applied to all companies
        else{
            echo "student applied to all companies";
        }
    }

    // check if inputted cid 
    function isCidValid($conn, $cid, $sid) {
        $qry = "SELECT cid, cname, quota FROM company AS C WHERE quota > 0 and NOT EXISTS( SELECT cid FROM apply AS A WHERE A.cid = C.cid and A.sid = " . $_SESSION['sid'].")";
        $result = mysqli_query($conn, $qry);
        if (!$result) {
            printf(mysqli_error($conn));
            exit();
        }

        $result_rows = mysqli_num_rows($result);
        
        if($result_rows > 0){
            // search cid in candidate companies' cid
            while($row = mysqli_fetch_array($result)) {
                if($row['cid'] == $cid) {
                    return true;
                }
            }
        }
        return false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Page</title>
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
        table {
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>


<div id="rightwrapper">
    <a class="btn btn-primary active" href="welcome.php">Go Back</a>            
    <a class="btn btn-primary active" href="logout.php">Log Out</a>
</div>

<div id="centerwrapper">
    <?php
        include_once 'conn.php';
        session_start();
        echo "<h2 class=\"page-header\" style=\"font-weight: bold;\">Candidate Companies</h2>";
        //echo "<h2 class=\"page-header\" style=\"font-weight: bold;\">Summer Intenship Applications of " . $_SESSION['sname']  . " (" . $_SESSION['sid'] . ")" . "</h2>";
    
        $sid = $_SESSION["sid"];
        displayCandidateCompaniesTable($conn, $sid);

        if( isset( $_POST['submitButton'])) {
            // check inputs
            $cid = $_POST['cid_inputted'];
            if( $cid == '') {
                echo "<script type='text/javascript'>alert('Please fill the field!');</script>"; 
                echo "<script LANGUAGE='JavaScript'> window.location.href = 'add.php' </script>";
            }
            if( isCidValid($conn, $cid, $sid) ) {
                // decrease quota by 1.
                $qry = "UPDATE company SET quota = quota - 1 WHERE cid='$cid'";
                $result = mysqli_query($conn, $qry);
                if (!$result) {
                    printf("An error occured while decreasing the quota of applied internship!" + mysqli_error($conn));
                    exit();
                }

                // add to apply database
                $qry = "INSERT INTO apply VALUES (" . $sid . "," . "'" . $cid . "');";
                $result = mysqli_query($conn, $qry);
                if (!$result) {
                    printf("An error occured while applying to the internship!" + mysqli_error($conn));
                    exit();
                }

                // success
                echo "<script LANGUAGE='JavaScript'> window.alert('Applied to Internship for $cid!'); </script>";
                // navigate to welcome.php
                echo "<script LANGUAGE='JavaScript'> window.location.href = 'welcome.php' </script>";

            }
            else {
                echo "<script type='text/javascript'>alert('Please submit a valid company ID!');</script>"; 
                echo "<script LANGUAGE='JavaScript'> window.location.href = 'add.php' </script>";
            }
        }

    ?>
    <form id="addCompanyForm" action="" method="post">
            <br>Enter the company ID you want to apply and press the Submit button <br><input type="text" name="cid_inputted" size="50" id="cid_inputted">
            <input type="submit" name="submitButton" class="btn btn-primary active" value="Submit">
    </form>
</div>


</body>
</html>

