<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch users
$users_result = $conn->query("SELECT * FROM users ORDER BY created_at ASC");

// Prepare chart data: count registrations per date
$registration_counts = [];
while($row = $users_result->fetch_assoc()) {
    $date = date('Y-m-d', strtotime($row['created_at']));
    if (!isset($registration_counts[$date])) {
        $registration_counts[$date] = 0;
    }
    $registration_counts[$date]++;
}

$dates = json_encode(array_keys($registration_counts));
$counts = json_encode(array_values($registration_counts));

// Reset pointer to reuse $users_result for table
$users_result->data_seek(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- Bootstrap + DataTables JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
  background: #1d1f27;
  margin: 0;
  font-family: Arial, sans-serif;
  color: white;
}
.sidebar {
  width: 230px;
  height: 100vh;
  background: #11131a;
  padding: 20px;
  position: fixed;
  top: 0;
  left: 0;
}
.sidebar h3 {
  color: #fff;
  margin-bottom: 30px;
}
.sidebar a {
  display: block;
  color: #ccc;
  padding: 12px;
  margin-bottom: 8px;
  border-radius: 8px;
  text-decoration: none;
  cursor: pointer;
}
.sidebar a:hover {
  background-color: #2b2e33;
  color: #fff;
}
.main-content {
  margin-left: 250px;
  padding: 30px;
}
.table-box {
  background: rgba(0,0,0,0.4);
  padding: 20px;
  border-radius: 12px;
}
.chart-box {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 500px;
  background: transparent;
}
#registrationChart {
  max-width: 700px;
  max-height: 450px;
}
table.dataTable {
  background: #2b2e33;
  border-radius: 8px;
}
table.dataTable tbody tr {
  background-color: #2b2e33;
}
table.dataTable tbody tr:hover {
  background-color: #3a3e45;
}
</style>
</head>
<body>

<div class="sidebar">
  <h3>Dashboard</h3>
  <a id="usersLink">Users</a>
  <a id="chartsLink">Charts</a>
  <a href="logout.php" style="margin-top:40px; color:#ff6b6b;">Logout</a>
</div>

<div class="main-content">

  <h2>Welcome, <?php echo $_SESSION['fullname']; ?></h2>

  <!-- DATA TABLE -->
  <div id="usersBox" class="table-box">
    <table id="usersTable" class="table table-striped table-dark">
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
          <td><?= $row['id']; ?></td>
          <td><?= $row['fullname']; ?></td>
          <td><?= $row['email']; ?></td>
          <td><?= $row['gender']; ?></td>
          <td><?= $row['city']; ?></td>
          <td><?= $row['created_at']; ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>

  <!-- LINE CHART -->
  <div id="chartsBox" class="chart-box" style="display:none;">
    <canvas id="registrationChart"></canvas>
  </div>

</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#usersTable').DataTable({
        paging: true,
        searching: true,
        info: false,
        lengthChange: false,
        responsive: true
    });

    // Toggle views
    $('#usersLink').click(function() {
        $('#chartsBox').hide();
        $('#usersBox').show();
    });
    $('#chartsLink').click(function() {
        $('#usersBox').hide();
        $('#chartsBox').show();
    });

    // Chart.js Line Chart with integer y-axis
    const ctx = document.getElementById('registrationChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo $dates; ?>,
            datasets: [{
                label: 'Registrations per Day',
                data: <?php echo $counts; ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: '#36A2EB',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: { color: 'white', font: { size: 16 } },
                    grid: { color: 'rgba(255,255,255,0.1)' }
                },
                y: {
                    ticks: { 
                        color: 'white', 
                        font: { size: 16 },
                        stepSize: 1 // force integer steps
                    },
                    grid: { color: 'rgba(255,255,255,0.1)' },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    labels: { color: 'white', font: { size: 18 } }
                }
            }
        }
    });
});
</script>

</body>
</html>
