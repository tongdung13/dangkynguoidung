<!DOCTYPE html>
<html>
<head>
<style>
    .error {
        color: #FF0000;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: solid 1px #ccc;
    }
    form {
        width: 450px;
    }
</style>
</head>
<body>

<?php

    function loadRegistrations($filename) {
        $jsondata = file_get_contents($filename);
        $arr_data = json_encode($jsondata, true);
        return $arr_data;
    }

    function saveDataJSON($filename, $name, $email, $phone) {
        try {
            $contact = array(
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone
            );
            $arr_data = loadRegistrations($filename);
            array_push($arr_data,$contact);
            $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);
            file_put_contents($filename, $jsondata);
            echo "luu tru thanh cong";
        } catch (Exception $e) {
            echo 'loi: ', $e->getMessage(), "\n";
        }
    }

    $nameErr = NULL;
    $emailErr = NULL;
    $phoneErr = NULL;
    $name = NULL;
    $email = NULL;
    $phone = NULL;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $has_error = false;

        if (empty($name)) {
            $nameErr ="ten de trong";
            $has_error = true;
        }

        if (empty($email)) {
            $emailErr = "emai de trong";
            $has_error = true;
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "email sai (xxx@xxx.xxx)!";
                $has_error = true;
            }
        }

        if (empty($phone)) {
            $phoneErr = "khong duoc de trong";
            $has_error = true;
        }

        if ($has_error === false) {
            saveDataJSON("data.json", $name, $email, $phone);
            $name = NULL;
            $email = NULL;
            $phone = NULL;
        }


    }
    ?>
    <h1>Registration Form</h1>

    <form method="post">
        <fieldset>
            <legend>Details</legend>
            Name: <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="error"> <?php echo $nameErr ;?>  </span>
            <br><br>
            E-mail: <input type="email" name="email" value="<?php echo $email; ?>">
            <span class="error"><?php echo $emailErr ?></span>
            <br><br>
            Phone: <input type="text" name="phone" value="<?php echo $phone  ?>">
            <span class="error"><?php echo $phoneErr ?></span>
            <br><br>
            <input type="submit" name="submit" value="Register">
            <p><span class="error"> required field</span> </p>
        </fieldset>
    </form>

<?php
$registrations = loadRegistration('data.json');
?>
<h1>Registration List</h1>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
    </tr>
    <?php foreach ($registrations as $registration): ?>
    <tr>
        <td><?= $registration['name']; ?></td>
        <td><?= $registration['email']; ?></td>
        <td><?= $registration['phone'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
