<?php
include_once './model/database.php';
include_once './model/person.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalCode'];

    $address = new Address($street, $city, $state, $postalCode);
    $person = new Person($name, $age, $address);
    $person->setId($id);
    $person->updateInDatabase($conn);

    header("Location: index.php");
}

$conn->close();
?>