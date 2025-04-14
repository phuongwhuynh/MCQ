<!-- <div class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
    >
        <h1 class="h2">Welcome Mr. B</h1>
    </div>
    <div class="card mb-4">
        <div class="card-body">
        <h5 class="card-title">Activity</h5>
        <p class="card-text text-muted">Test taken</p>
        <canvas id="activityChart"> </canvas>
        </div>
    </div>
</div> -->
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
      const activityCtx = document
        .getElementById("activityChart")
        .getContext("2d");
      const activityChart = new Chart(activityCtx, {
        type: "bar",
        data: {
          labels: [
            "TEST",
            "M1003",
            "M1005",
            "H1004",
            "P1001",
            "P3004",
            "P1004",
            "P3001",
            "P3008",
            "P3009",
          ],
          datasets: [
            {
              label: "Test taken",
              data: [10, 20, 60, 30, 60, 10, 60, 80, 60, 80],
              backgroundColor: "rgba(99, 102, 241, 0.5)",
              borderColor: "rgba(99, 102, 241, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
            },
          },
        },
      });

      const timeRemainingCtx = document
        .getElementById("timeRemainingChart")
        .getContext("2d");
      const timeRemainingChart = new Chart(timeRemainingCtx, {
        type: "bar",
        data: {
          labels: ["M1003", "M1005", "H1004", "P1001", "P3004"],
          datasets: [
            {
              label: "Time Remaining",
              data: [20, 36, 20, 36, 85],
              backgroundColor: "rgba(99, 102, 241, 0.5)",
              borderColor: "rgba(99, 102, 241, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
            },
          },
        },
      });
    </script>
