<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all users
$users_result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
body {
  margin: 0;
  padding: 0;
  min-height: 100vh;
  background: url("https://static.vecteezy.com/system/resources/previews/007/972/775/non_2x/dark-black-and-gray-blurred-background-has-a-little-abstract-light-free-photo.jpg") no-repeat center center/cover;
  color: white;
  font-family: Arial, sans-serif;
}
.container-box {
  width: 90%;
  max-width: 1000px;
  padding: 30px;
  background: rgba(0,0,0,0.65);
  border-radius: 18px;
  margin: 40px auto;
  box-sizing: border-box;
}
h2 {
  text-align: center;
  font-size: 2.4rem;
  margin-bottom: 20px;
}
a.logout, a.charts-link {
  display: inline-block;
  color: #fff;
  text-decoration: none;
  margin-bottom: 20px;
  font-weight: bold;
  margin-right: 15px;
}
a.logout:hover, a.charts-link:hover { color: #ccc; }

/* DataTables styling for dark theme */
.dataTables_wrapper { color: white; }
table.dataTable {
  width: 100%;
  border-collapse: collapse;
  background: rgba(255,255,255,0.05);
  border-radius: 10px;
  overflow: hidden;
}
table.dataTable th,
table.dataTable td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}
table.dataTable th { background: rgba(255,255,255,0.1); }
table.dataTable tr:hover { background: rgba(255,255,255,0.15); }
</style>
</head>
<body>

<div class="container-box">
  <h2>Welcome, <?php echo $_SESSION['fullname']; ?></h2>
  <a href="logout.php" class="logout">Logout</a>
  <a href="charts.php" class="charts-link">View Charts</a>

  <table id="usersTable" class="display">
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Gender</th>
        <th>City</th>
        <th>Registered At</th>
      </tr>
    </thead>
    <tbody>
    <?php while($row = $users_result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['fullname']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['gender']; ?></td>
        <td><?php echo $row['city']; ?></td>
        <td><?php echo $row['created_at']; ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>

<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        paging: true,
        searching: true,
        info: false,
        lengthChange: false,
    });
});
</script>

</body>
</html>
