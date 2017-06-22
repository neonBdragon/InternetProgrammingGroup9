<?php
//signup.php
include 'layout/connect.php';
include 'layout/header.php';
 
echo '<h3>Sign up</h3>';
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    /*the form hasn't been posted yet, display it
      note that the action="" will cause the form to post to the same page it is on */
    echo
        '<form method="post" action="">
            <table border="3">
                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="userName" />
                    </td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="password" name="userPass">
                    </td>
                </tr>
                <tr>
                    <td>Password again: </td>
                    <td>
                        <input type="password" name="userPassCheck">
                    </td>
                </tr>
                <tr>
                    <td>E-mail: </td>
                    <td>
                        <input type="email" name="userEmail">
                    </td>
                </tr>
                <tr>    
                    <td>
                        <input type="submit" value="Sign Up" />
                    </td>
                </tr>
            </table>
        </form>';
}
else
{
    /* so, the form has been posted, we'll process the data in three steps:
        1.  Check the data
        2.  Let the user refill the wrong fields (if necessary)
        3.  Save the data 
    */
    $errors = array(); /* declare the array for later use */
     
    if(isset($_POST['userName']))
    {
        //the user name exists
        if(!ctype_alnum($_POST['userName']))
        {
            $errors[] = 'The username can only contain letters and digits.';
        }
        if(strlen($_POST['userName']) > 30)
        {
            $errors[] = 'The username cannot be longer than 30 characters.';
        }
    }
    else
    {
        $errors[] = 'The username field must not be empty.';
    }
     
     
    if(isset($_POST['userPass']))
    {
        if($_POST['userPass'] != $_POST['userPassCheck'])
        {
            $errors[] = 'The two passwords did not match.';
        }
    }
    else
    {
        $errors[] = 'The password field cannot be empty.';
    }
     
    if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
    {
        echo 'Uh-oh.. a couple of fields are not filled in correctly..';
        echo '<ul>';
        foreach($errors as $key => $value) /* walk through the array so all the errors get displayed */
        {
            echo '<li>' . $value . '</li>'; /* this generates a nice error list */
        }
        echo '</ul>';
    }
    else
    {
        //the form has been posted without, so save it
        //notice the use of mysql_real_escape_string, keep everything safe!
        //also notice the sha1 function which hashes the password
        // $sql = $dbConnection->prepare("INSERT INTO
        //             users(useName, userPass, userEmail ,userDate, userLevel)
        //         VALUES('" . mysql_real_escape_string($_POST['userName']) . "',
        //                '" . sha1($_POST['userPass']) . "',
        //                '" . mysql_real_escape_string($_POST['useEmail']) . "',
        //                 NOW(),
        //                 0)");

        try
        {

            $sql = $dbConnection->prepare("INSERT INTO users (userName, userPass, userEmail ,userDate, userLevel) VALUES (:userName, :userPass, :userEmail, NOW(), 0)");

            $sql->bindParam(':userName', $userName);
            $sql->bindParam(':userPass', $userPass);
            $sql->bindParam(':userEmail', $userEmail);

            $userName = $_POST["userName"];
            $userPass = SHA1($_POST["userPass"]);
            $userEmail = $_POST["userEmail"];

            $sql->execute();
                            
            //$result = mysql_query($sql);
            //$result = $sql->fetch(PDO::FETCH_ASSOC);
            // if(!$result)
            // {
            //     //something went wrong, display the error
            //     echo 'Something went wrong while registering. Please try again later.';
            //     //echo mysql_error(); //debugging purposes, uncomment when needed
            // }
            // else
            // {
            //     echo 'Successfully registered. You can now <a href="signin.php">sign in</a> and start posting! :-)';
            // }

            echo 'Successfully registered. You can now <a href="signin.php">sign in</a> and start posting! :-)';

        }

        catch (PDOException $e)
        {
            die("Could not connect: " . $e->getMessage());
        }

    }
}
 
include 'layout/footer.php';
?>