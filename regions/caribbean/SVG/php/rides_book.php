<?php
session_start();
// Connect to MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "islandMovers_vc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
// $name = $_POST['name'];
// $email = $_POST['email'];

$id = $_SESSION["id"];


$vehicle = $_POST['vehicle'];
$pickup = $_POST['pickup'];
$destination = $_POST['destination'];
$time = $_POST['time'];
// $current_date = date('Y-m-d'); // Returns the current date in the format YYYY-MM-DD
date_default_timezone_set('America/St_Vincent');
$time_date = date('Y-m-d H:i:s');

if (date('l') == "Tuesday") {
    $price = 5.00;
} else {
    if ($vehicle == 4) { //Sedan
        $price = 15.00;
    } elseif ($vehicle == 5) { // SUV
        $price = 10.00;
    } elseif ($vehicle == 6) { // Hatchback
        $price = 20.00;
    }
}

if ($id == "") {
    echo '<script>
        alert("Please login using the form first, then book a ride.");
        window.location.href = "../rides_vc.php";
    </script>';
    //header("Location: ../rides_fl.php");
}
//echo $vehicle . " " . $pickup . " " . $destination . " " . $time . " " . $current_date;

// Insert data into database
$sql = "INSERT INTO rides (UserID, VehicleID, PickupLocation, Destination, RideDateTime, RideStatus , RideCost) VALUES ('$id', '$vehicle', '$pickup', '$destination', '$time_date', 'Pending', $price)";

if ($conn->query($sql) === TRUE) {
        // Redirect to another page
        $sql = "SELECT * FROM rides WHERE UserID = '$id'";
        $result = $conn->query($sql);

        $output = "";

        if ($result->num_rows > 0) {
            // Output data of each row

            while($row = $result->fetch_assoc()) {
                // $output .= $row["RideID"]. " " . $row["UserID"]. " " . $row["VehicleID"]. " " . $row["PickupLocation"]. " " . $row["Destination"]. " " . $row["RideDateTime"]. " " . $row["RideStatus"]. " " . $row["RideCost"]. "<br>";
                // You can display other columns as needed
                $output .= '<tr>';
                $output .= '<td>' . $row["RideID"] . '</td>';
                $output .= '<td>' . $row["UserID"] . '</td>';
                $output .= '<td>' . $row["VehicleID"] . '</td>';
                $output .= '<td>' . $row["PickupLocation"] . '</td>';
                $output .= '<td>' . $row["Destination"] . '</td>';
                $output .= '<td>' . $row["RideDateTime"] . '</td>';
                $output .= '<td>' . $row["RideStatus"] . '</td>';
                $output .= '<td>' . $row["RideCost"] . '</td>';
                $output .= '</tr>';
            }

            $_SESSION["output"] = $output;
            header("Location: ../rides_vc.php");
        } else {
            $output .= "0 results found for the email: " . $entered_email;
            $_SESSION["output"] = $output;
            header("Location: ../rides_vc.php");
        }
            //echo "ID exists!";
        //exit;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
