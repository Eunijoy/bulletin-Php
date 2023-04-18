<?php
    if (isset($_POST["btnSave"])) {
        // Connect to the database
        require_once "connection.php";

        $subject_entry   = $con->real_escape_string($_POST["subject"]);
        $details_entry   = $con->real_escape_string($_POST["details"]);
        $date = $con->real_escape_string($_POST["date"]);

        if ($stmt = $con->prepare("INSERT INTO `entries`(`subjects`, `entry_details`, `dates`) VALUES (?, ?, ?)")) {
            $stmt->bind_param("sss", $subject_entry, $details_entry, $date);
            $stmt->execute();
            $stmt->close();
            $msg = '<div class="msg msg-create">Bulletin details saved successfully.</div>';
        } else {
            $msg = '<div class="msg">Prepare() failed: '.htmlspecialchars($con->error).'</div>';
        }

        // Close database connection
        $con->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin Board Entry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if(isset($msg)){ echo $msg; }?>
    <main class="container">
        <div class="wrapper">
            <h1>Add new bulletin entry</h1>
        </div>
        <div class="wrapper">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="frmCreate">
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="details" id="" cols="66" rows="10" placeholder="Type details.."></textarea>
                <input type="date" name="date" placeholder="Date" required>
                <div class="btnWrapper">
                    <button type="submit" name="btnSave">Add</button>
                    <a href="main.php" class="btnHome">skip</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>