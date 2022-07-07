<?php
    session_start();
    if(!isset($_SESSION["customer"])) $_SESSION['customer']=array('email' => "", 'name' => "", 'username' => "", 'telephone' => "", 'age'=>"",'gender' => "", 'password'=>"");
    
    //database details
    $host = "localhost";
    $dbusername = "id18810391_group2sbrs";
    $dbpassword = "P{6Vm%=aaMtE)0OZ";
    $dbname = "id18810391_ridesharingdb";
    if(isset($_POST['signup']))//customer sign up
    {
        #========================
        $email = $_POST['email'];
        $name = $_POST['fullname'];
        $username = $_POST['username'];
        $telephone = $_POST['telephone'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];

        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }
        //This below line is a code to Send form entries to database
        $sql = "INSERT INTO customer_account (email, name, username, telephone, age, gender, password) VALUES ('$email', '$name', '$username', '$telephone','$age','$gender','$password')";
        
      //fire query to save entries and check it with if statement
        $rs = mysqli_query($con, $sql);
        if($rs)
        {
            header('Location: customer-login.html');
        }
      //connection closed.
        mysqli_close($con);
    }
    else if(isset($_POST['login']))//customer login
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }

        $sql = "SELECT * FROM customer_account WHERE username = '$username'";
        $res = mysqli_query($con,$sql) or die; //if there is no matching username, then exit from this php code
        while($row = mysqli_fetch_array($res)){
            $_SESSION['customer']['email']=$row["email"];
            $_SESSION['customer']['name']=$row["name"];
            $_SESSION['customer']['username']=$row["username"];
            $_SESSION['customer']['telephone']=$row["telephone"];
            $_SESSION['customer']['age']=$row["age"];
            $_SESSION['customer']['gender']=$row["gender"];
            $_SESSION['customer']['password']=$row["password"];
            $correct_pass = $row["password"];
        }
        if($password == $correct_pass)
        {
            //connection closed.
            mysqli_close($con);
            header('Location: customer-order.html');
            exit;
        }else{
            echo "false";
        }
      //connection closed.
        mysqli_close($con);
    }
    else if(isset($_POST['order']))//customer order
    {
        $pickup = $_POST['pickup'];
        $destination = $_POST['destination'];
        $price = $_POST['price'];

        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }
        //This below line is a code to Send form entries to database
        $username = $_SESSION['customer']['username'];
        $name = $_SESSION['customer']['name'];
        $sql = "INSERT INTO orders (customer_username, customer_name, pickup, destination, price) VALUES ('$username','$name','$pickup', '$destination','$price')";
      //fire query to save entries and check it with if statement
        $rs = mysqli_query($con, $sql);
        if($rs)
        {
            header('Location: customer-order.html');
        }else{
            echo "order failed";
        }
      //connection closed.
        mysqli_close($con);
    }else if(isset($_POST['getdata']))//customer get data
    {
        print_r(json_encode($_SESSION['customer'],JSON_UNESCAPED_SLASHES));
    }else if(isset($_POST['edit']))//customer edit
    {
        
        $email = $_POST['email'];
        $name = $_POST['fullname'];
        $username = $_POST['username'];
        $telephone = $_POST['telephone'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];

        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }
        //This below line is a code to Send form entries to database
        $sql = "UPDATE customer_account SET email='$email', name='$name', username='$username', telephone='$telephone', age='$age', gender='$gender', password='$password' WHERE username='$username'";
        
      //fire query to save entries and check it with if statement
        $rs = mysqli_query($con, $sql);
        if($rs)
        {
            $sql = "SELECT * FROM customer_account WHERE username = '$username'";
            $res = mysqli_query($con,$sql) or die; //if there is no matching username, then exit from this php code
            while($row = mysqli_fetch_array($res)){
                $_SESSION['customer']['email']=$row["email"];
                $_SESSION['customer']['name']=$row["name"];
                $_SESSION['customer']['username']=$row["username"];
                $_SESSION['customer']['telephone']=$row["telephone"];
                $_SESSION['customer']['age']=$row["age"];
                $_SESSION['customer']['gender']=$row["gender"];
                $_SESSION['customer']['password']=$row["password"];
            }
            echo "<script>
            alert('Edit Successful');
            window.location.href='customer-order.html';
            </script>";
            // header('Location: customer-order.html');
        }
      //connection closed.
        mysqli_close($con);
    }else if(isset($_POST['showTransaction']))//customer show transactions
    {
        $customer_username = $_SESSION['customer']['username'];
        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }

        $sql = "SELECT * FROM transaction WHERE customer_username = '$customer_username'";
        $res = mysqli_query($con,$sql) or die; //if there is no matching username, then exit from this php code
        $alldata = array();
        while($row = mysqli_fetch_assoc($res)){
            array_push($alldata,$row);
            }
        print_r(json_encode($alldata,JSON_UNESCAPED_SLASHES));
      //connection closed.
        mysqli_close($con);
    }else if(isset($_POST['deleteTransaction']))//driver show transactions
    {
        $transaction_id = $_POST['transaction_id'];
        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }

        $sql = "DELETE FROM transaction WHERE transaction_id=$transaction_id";
        $res = mysqli_query($con,$sql) or die; //if there is no matching username, then exit from this php code
        if($res){
            echo "succesfully deleted";
        }
      //connection closed.
        mysqli_close($con);
    }
?>