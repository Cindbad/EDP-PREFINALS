<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch users for chart
$result = $conn->query("SELECT gender, COUNT(*) as count FROM users GROUP BY gender");
$gender_data = ['Male' => 0, 'Female' => 0];
while($row = $result->fetch_assoc()) {
    $gender_data[$row['gender']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Charts</title>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
  max-width: 800px;
  padding: 30px;
  background: rgba(0,0,0,0.65);
  border-radius: 18px;
  margin: 40px auto;
  box-sizing: border-box;
  text-align: center;
}
h2 {
  font-size: 2.4rem;
  margin-bottom: 20px;
}
a.logout, a.dashboard-link {
  display: inline-block;
  color: #fff;
  text-decoration: none;
  margin-bottom: 20px;
  font-weight: bold;
  margin-right: 15px;
}
a.logout:hover, a.dashboard-link:hover { color: #ccc; }

.chart-container {
  margin-top: 30px;
  background: rgba(255,255,255,0.05);
  padding: 20px;
  border-radius: 12px;
}
</style>
</head>
<body>

<div class="container-box">
  <h2>Charts</h2>
  <a href="dashboard.php" class="dashboard-link">Back to Dashboard</a>
  <a href="logout.php" class="logout">Logout</a>

  <div class="chart-container">
    <canvas id="genderChart" width="400" height="200"></canvas>
  </div>
</div>

<script>
const ctx = document.getElementById('genderChart').getContext('2d');
const genderChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Male', 'Female'],
        datasets: [{
            label: 'Gender Distribution',
            data: [<?php echo $gender_data['Male']; ?>, <?php echo $gender_data['Female']; ?>],
            backgroundColor: ['#36A2EB', '#FF6384'],
            borderColor: ['#fff', '#fff'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { color: 'white', font: { size: 14 } } }
        }
    }
});
</script>

</body>
</html>
