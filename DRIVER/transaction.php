<?php
    $host = "localhost";
    $dbusername = "id18810391_group2sbrs";
    $dbpassword = "P{6Vm%=aaMtE)0OZ";
    $dbname = "id18810391_ridesharingdb";
    if(isset($_POST['accept']))//driver accepts
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
            echo "order deleted";
        }
      //connection closed.
        mysqli_close($con);
    }
?>