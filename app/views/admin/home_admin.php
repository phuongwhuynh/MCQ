
<div class="container-fluid px-0" role="main">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
  >
    <h1 class="h2">Welcome Mr. B</h1>
  </div>
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Activity</h5>
      <p class="card-text text-muted">Test taken</p>
      <canvas id="activityChart"></canvas>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            controller: 'test',
            ajax: 1,
            action: 'getTestActivity'
        })
    })
    .then(response => response.json())
    .then(data => {
        const labels = data.date; 
        const testCounts = data.attemptCounts; 

        const activityCtx = document.getElementById("activityChart").getContext("2d");
        const activityChart = new Chart(activityCtx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: "Users' Attempts: ",
                    data: testCounts,
                    backgroundColor: "rgba(99, 102, 241, 0.5)",
                    borderColor: "rgba(99, 102, 241, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }

                    }
                }
            }
        });
    })
    .catch(error => {
        console.error("Error loading activity data:", error);
    });
});
</script>
