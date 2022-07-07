<?php
    session_start();
    if(!isset($_SESSION["driver"])) $_SESSION['driver']=array('email' => "", 'name' => "", 'username' => "", 'telephone' => "", 'age'=>"",'gender' => "", 'vehicle' => "", 'password'=>"");
    
    
    //database details
    $host = "localhost";
    $dbusername = "id18810391_group2sbrs";
    $dbpassword = "P{6Vm%=aaMtE)0OZ";
    $dbname = "id18810391_ridesharingdb";
    if(isset($_POST['signup']))//driver sign up
    {
        $email = $_POST['email'];
        $name = $_POST['fullname'];
        $username = $_POST['username'];
        $telephone = $_POST['telephone'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $vehicle = $_POST['vehicle'];
        $password = $_POST['password'];

        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }
        //This below line is a code to Send form entries to database
        $sql = "INSERT INTO driver_account (email, name, username, telephone, age, gender, vehicle, password) VALUES ('$email', '$name', '$username', '$telephone','$age','$gender', '$vehicle','$password')";
      //fire query to save entries and check it with if statement
        $rs = mysqli_query($con, $sql);
        if($rs)
        {
            echo "Successfully saved";
        }
      //connection closed.
        mysqli_close($con);
    }
    else if(isset($_POST['login']))//driver login
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

        $sql = "SELECT * FROM driver_account WHERE username = '$username'";
        $res = mysqli_query($con,$sql) or die; //if there is no matching username, then exit from this php code
        while($row = mysqli_fetch_array($res)){
            $_SESSION['driver']['email']=$row["email"];
            $_SESSION['driver']['name']=$row["name"];
            $_SESSION['driver']['username']=$row["username"];
            $_SESSION['driver']['telephone']=$row["telephone"];
            $_SESSION['driver']['age']=$row["age"];
            $_SESSION['driver']['gender']=$row["gender"];
            $_SESSION['driver']['vehicle']=$row["vehicle"];
            $_SESSION['driver']['password']=$row["password"];
            $correct_pass = $row["password"];
        }
        if($password == $correct_pass)
        {
            //connection closed.
            mysqli_close($con);
            header('Location: driver-order.html');
            exit;
        }else{
            echo "false";
        }
      //connection closed.
        mysqli_close($con);
    }
    else if(isset($_POST['showdata']))//driver show data
    {
        // $pickup = $_POST['pickup'];
        // $destination = $_POST['destination'];
        // $price = $_POST['price'];
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        $sql = "SELECT * FROM orders";
        $driver_data = array('driver_username'=>$_SESSION['driver']['username'], 'driver_name'=>$_SESSION['driver']['name']);
        $res = mysqli_query($con,$sql) or die; //if there is no matching username, then exit from this php code
        $alldata = array();
        while($row = mysqli_fetch_assoc($res)){
            array_push($alldata,array_merge($driver_data,$row));
            }
        print_r(json_encode($alldata,JSON_UNESCAPED_SLASHES));

    //     //create connection
    //     $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
    //     //check connection if it is working or not
    //     if (!$con)
    //     {
    //         die("Connection failed!" . mysqli_connect_error());
    //     }
    //     //This below line is a code to Send form entries to database
    //     $username = $_SESSION['username'];
    //     $name = $_SESSION['name'];
    //     $sql = "INSERT INTO transaction (customer_username, customer_name, pickup, destination, price) VALUES ('$username','$name','$pickup', '$destination','$price')";
    //   //fire query to save entries and check it with if statement
    //     $rs = mysqli_query($con, $sql);
    //     if($rs)
    //     {
    //         echo "order succeed";
    //     }else{
    //         echo "order failed";
    //     }
      //connection closed.
        mysqli_close($con);
    }else if(isset($_POST['accept']))//driver accepts
    {
        $order_id = $_POST['order_id'];
        $customer_username = $_POST['customer_username'];
        $driver_username = $_POST['driver_username'];
        $customer_name = $_POST['customer_name'];
        $driver_name = $_POST['driver_name'];
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
        $sql = "INSERT INTO transaction (customer_username, driver_username, customer_name, driver_name, pickup, destination, price) VALUES ('$customer_username', '$driver_username', '$customer_name', '$driver_name','$pickup','$destination', '$price')";
      //fire query to save entries and check it with if statement
        $rs = mysqli_query($con, $sql);
        if($rs)
        {
            $sql = "DELETE FROM orders WHERE order_id =$order_id";
          //fire query to save entries and check it with if statement
            $rsl = mysqli_query($con, $sql);
          //connection closed.
            if($rsl){
                //connection closed.
                mysqli_close($con);
                header('Location: transaction.html');
                exit;
            }
        }
        mysqli_close($con);
        
    }else if(isset($_POST['reject']))//driver rejects
    {
        $order_id = $_POST['order_id'];
        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }
        //This below line is a code to Send form entries to database
        $sql = "DELETE FROM orders WHERE order_id =$order_id";
      //fire query to save entries and check it with if statement
        $rs = mysqli_query($con, $sql);
        if($rs)
        {
            header('Location: driver-order.html');
        }
      //connection closed.
        mysqli_close($con);
    }else if(isset($_POST['showTransaction']))//driver show transactions
    {
        $driver_username = $_SESSION['driver']['username'];
        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }

        $sql = "SELECT * FROM transaction WHERE driver_username = '$driver_username'";
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
    }else if(isset($_POST['edit']))//driver edit
    {
        $email = $_POST['email'];
        $name = $_POST['fullname'];
        $username = $_POST['username'];
        $telephone = $_POST['telephone'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $vehicle = $_POST['vehicle'];
        $password = $_POST['password'];

        //create connection
        $con = mysqli_connect($host, $dbusername, $dbpassword, $dbname);
        //check connection if it is working or not
        if (!$con)
        {
            die("Connection failed!" . mysqli_connect_error());
        }
        //This below line is a code to Send form entries to database
        $sql = "UPDATE driver_account SET email='$email', name='$name', username='$username', telephone='$telephone', age='$age', gender='$gender', vehicle='$vehicle', password='$password' WHERE username='$username'";
        
      //fire query to save entries and check it with if statement
        $rs = mysqli_query($con, $sql);
        if($rs)
        {
            $sql = "SELECT * FROM driver_account WHERE username = '$username'";
            $res = mysqli_query($con,$sql) or die; //if there is no matching username, then exit from this php code
            while($row = mysqli_fetch_array($res)){
                $_SESSION['driver']['email']=$row["email"];
                $_SESSION['driver']['name']=$row["name"];
                $_SESSION['driver']['username']=$row["username"];
                $_SESSION['driver']['telephone']=$row["telephone"];
                $_SESSION['driver']['age']=$row["age"];
                $_SESSION['driver']['gender']=$row["gender"];
                $_SESSION['driver']['vehicle']=$row["vehicle"];
                $_SESSION['driver']['password']=$row["password"];
            }
            echo "<script>
            alert('Edit Succesful');
            window.location.href='driver-order.html';
            </script>";
        }else{
            echo "edit failed";
        }
      //connection closed.
        mysqli_close($con);
    }else if(isset($_POST['getdata']))//driver get data
    {
        print_r(json_encode($_SESSION['driver'],JSON_UNESCAPED_SLASHES));
    }
?>