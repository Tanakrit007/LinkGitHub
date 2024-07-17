<?php

class Person {
    private $id;
    private $name;
    private $age;
    private $street;
    private $city;
    private $state;
    private $postalCode;

    public function __construct($name, $age, $street, $city, $state, $postalCode) {
        $this->name = $name;
        $this->age = $age;
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAge() {
        return $this->age;
    }

    public function getStreet() {
        return $this->street;
    }

    public function getCity() {
        return $this->city;
    }

    public function getState() {
        return $this->state;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function saveToDatabase($conn) {
        $stmt = $conn->prepare("INSERT INTO persons (name, age, street, city, state, postal_code) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("sissss", 
            $this->name, 
            $this->age, 
            $this->street, 
            $this->city, 
            $this->state, 
            $this->postalCode
        );
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
        $stmt->close();
    }

    public static function getAll($conn, $offset, $limit) {
        $stmt = $conn->prepare("SELECT * FROM persons LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $persons = [];
        while ($row = $result->fetch_assoc()) {
            $person = new Person($row['name'], $row['age'], $row['street'], $row['city'], $row['state'], $row['postal_code']);
            $person->setId($row['id']);
            $persons[] = $person;
        }
        $stmt->close();
        return $persons;
    }

    public function updateInDatabase($conn) {
        $stmt = $conn->prepare("UPDATE persons SET name=?, age=?, street=?, city=?, state=?, postal_code=? WHERE id=?");
        $stmt->bind_param("sissssi", 
            $this->name, 
            $this->age, 
            $this->street, 
            $this->city, 
            $this->state, 
            $this->postalCode,
            $this->id
        );
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
        $stmt->close();
    }

    public static function deleteFromDatabase($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM persons WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
        $stmt->close();
    }
}
?>
