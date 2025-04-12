<?php
$current_page = isset($_GET['num_page']) ? $_GET['num_page'] : 1;
?>
<div class="container my-4">
  <h3 class="mb-4">
    <i class="bi bi-clock-history me-2 text-secondary"></i>Finished Test Attempts
  </h3>

  <!-- History List -->
  <div class="list-group mb-4">
    <a href="index.php?page=past_attempt&test_attempt_id=1&num_page=<?php echo $current_page; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center shadow-sm">
      <div>
        <h5 class="mb-1">English Grammar Test</h5>
        <small class="text-muted">Finished at: 2025-04-12 11:20</small>
      </div>
      <span class="badge bg-success rounded-pill fs-6">85%</span>
    </a>

    <a href="index.php?page=past_attempt&test_attempt_id=2&num_page=<?php echo $current_page; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center shadow-sm">
      <div>
        <h5 class="mb-1">Science Quiz</h5>
        <small class="text-muted">Finished at: 2025-04-10 14:45</small>
      </div>
      <span class="badge bg-success rounded-pill fs-6">92%</span>
    </a>
  </div>

  <nav>
    <ul class="pagination justify-content-center">
      <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
      <li class="page-item active"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
  </nav>
</div>
