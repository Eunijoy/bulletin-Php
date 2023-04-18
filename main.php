<?php
    // Connect to the database
    require_once "connection.php";

    // Delete Table data
    if (isset($_GET["del"])) {
        $id = preg_replace('/\D/', '', $_GET["del"]); 
        if ($stmt = $con->prepare("DELETE FROM `entries` WHERE `id`=?")) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            $msg = '<div class="msg msg-delete">Entry details deleted successfully.</div>';
        } else {
            die('prepare() failed: ' . htmlspecialchars($con->error));
        }
    }

    // Display Table data
    $tabledata = "";
    $sqlsearch = "";
    if (isset($_POST["btnSearch"])) {
        $keywords = $con->real_escape_string($_POST["txtSearch"]);
        $searchTerms = explode(' ', $keywords);
        $searchTermBits = array();
        foreach ($searchTerms as $key => &$term) {
            $term = trim($term);
            $searchTermBits[] = " `subjects` LIKE '%$term%' OR `email` LIKE '%$term%' OR `entries` LIKE '%$term%'";
        }
        $sqlsearch = " WHERE " . implode(' AND ', $searchTermBits);
    }

    if ($stmt = $con->prepare("SELECT * FROM `entries` $sqlsearch")) {
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tabledata .= '<tr>
                                <td>'.$row["subjects"].'</td>
                                <td>'.$row["entry_details"].'</td>
                                <td>'.$row["dates"].'</td>
                                <td>
                                    <a href="main.php?del='.$row["id"].'" class="btnAction btnDelete" title="Delete contact details">&#10006;</a>
                                </td>
                            </tr>';
            }
        } else {
            $tabledata= '<tr><td colspan="4" style="text-align: center; padding:30px 0;">Nothing to display</td></tr>';
        }

        $stmt->close();
    } else {
        die('prepare() failed: ' . htmlspecialchars($con->error));
    }

    // Close database connection
    $con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin Entry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if(isset($msg)){ echo $msg; }?>
    <main class="container">
        <div class="wrapper">
            <h1>Bulletin Board View</h1>
        </div>
        <div class="wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Details</th>
                        <th>Date </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        echo $tabledata;
                    ?>
                </tbody>
            </table>
            <div class="wrapper">
            <a href="index.php" class="btnCreate" title="Create new contact">New Bulletin Entry</a>
        </div>
        </div>
    </main>
</body>
</html>