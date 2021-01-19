<?php require_once('./private/initialize.php'); ?>

<?php
// if we pressed the submit button on login page
if(isset($_POST['Submit'])){
    // get legal SQL string for use in an SQL statement, with current connection and what we entered in textbox
    // $_POST['input name']
    $uname = mysqli_real_escape_string($conn,$_POST['txt_uname']);
    $password = mysqli_real_escape_string($conn,$_POST['txt_pwd']);

    // if we entered something in the username and password box
    if ($uname != "" && $password != ""){

        // Query: User_doc = # of users who has the matched entered username and password
        $sql_query_doc = "select count(*) as User_doc 
                    from Link_Doctor_Account
                    where username='".$uname."' and password='".$password."'";
        // Performs the query against the the connected database
        // Return: a mysql_result object
        $result_doc = mysqli_query($conn,$sql_query_doc);
        // Fetch a result row as an associative, a numeric array, or both
        // Return: an array of strings that corresponds to the fetched row 
        $row_doc = mysqli_fetch_assoc($result_doc);
        // In the rows we get from the query: we want the User_doc
        $count_doc = $row_doc['User_doc'];

        // Do the same thing for Resident Account
        $sql_query_res = "select count(*) as User_res 
                    from Link_Personal_Account
                    where username='".$uname."' and password='".$password."'";
        $result_res = mysqli_query($conn, $sql_query_res);
        $row_res = mysqli_fetch_assoc($result_res);
        $count_res = $row_res['User_res'];
 
        // an account = doctor account OR resident account OR invalid
        // check if the # of account possible > 0 for both Doctor and Resident
        //      True: set the session and redirect to protected page
        //      False: print error message
        if($count_doc > 0){
            $_SESSION['uname'] = $uname;
            $_SESSION['Active'] = true;
            // echo "Successfully login in as a Doctor: " . $uname;
            header('location: public/doctor/index.php');
        }else if ($count_res > 0){
            $_SESSION['uname'] = $uname;
            $_SESSION['Active'] = true;
            // echo "Successfully login in as a Resident: " . $uname;
            header('location: public/resident/index.php');
        }else{
            echo "Invalid username and password";
        }
    }else{
        // if one of the username and password info missing
        echo "Empty username or password";
    }
}
?>

<html>
    <head>
        <title>Pandemic Tracker</title>
    </head>
    <body>
        <h1> Pandemic Tracker Sign In Page</h1>
        <form action="./index.php" method="post"> 
            <label>Username</label>
                <input type="text" name="txt_uname" placeholder="Username" />
            <br>
            <br>
            <label>Password </label>
            <input type="password" name="txt_pwd" placeholder="Password"/>
            <br>
            <br>
                <button name = "Submit" type="submit" value="Login in">Sign In</button>
        </form>

    </body>
</html>

<?php include(PRIVATE_P . '/footer.php'); ?>