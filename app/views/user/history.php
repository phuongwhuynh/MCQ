<div class="container my-4">
  <h3 class="mb-4">
    <i class="bi bi-clock-history me-2 text-secondary"></i>Finished Test Attempts
  </h3>

  <!-- Dynamic attempt list here -->
  <div id="attempt-list" class="list-group mb-4"></div>

  <!-- Pagination -->
  <nav>
    <ul id="pagination" class="pagination justify-content-center"></ul>
  </nav>
</div>

<script>
<?php
$currentPage = isset($_GET['num_page']) ? (int)$_GET['num_page'] : 1;
?>
let currentPage = <?= $currentPage ?>;
</script>
<script>
function fetchFinishedAttempts(page = 1) {
  const requestData = {
    ajax: 1,
    controller: "history",
    action: "getHistory",
    page: page,
    limit: 10  // Set limit to 10
  };

  fetch('index.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(requestData),
  })
    .then(res => res.json())
    .then(data => {
      if (data.error) return alert(data.error);
      currentPage = page;  
      renderAttempts(data.attempts, page);
      renderPagination(data.total, page);
    })
    .catch(err => console.error("Fetch error:", err));
}

function renderAttempts(attempts, page) {
  const list = document.getElementById("attempt-list");
  list.innerHTML = "";

  if (attempts.length === 0) {
    list.innerHTML = `<div class="alert alert-info">No finished attempts found.</div>`;
    return;
  }

  for (const item of attempts) {
    const percent = Math.round((item.score / item.total_questions) * 100);
    const badgeClass = percent < 50 ? 'bg-danger' : 'bg-success'; // 🔥 red if < 50%

    const html = `
      <a href="past-attempt/${item.attempt_id}/${page}"
         class="list-group-item list-group-item-action d-flex justify-content-between align-items-center shadow-sm">
        <div>
          <h5 class="mb-1">${item.test_name}</h5>
          <small class="text-muted">Started at: ${item.start_time}</small>
        </div>
        <span class="badge ${badgeClass} rounded-pill fs-6">${percent}%</span>
      </a>
    `;
    list.insertAdjacentHTML("beforeend", html);
  }
}


function renderPagination(total, current) {
  const pageCount = Math.ceil(total / 10); // Ensure 10 items per page
  const ul = document.getElementById("pagination");
  ul.innerHTML = "";

  const addPage = (p, label = p, disabled = false, active = false) => {
    const li = document.createElement("li");
    li.className = `page-item${disabled ? " disabled" : ""}${active ? " active" : ""}`;
    li.innerHTML = `<a class="page-link" href="#" data-page="${p}">${label}</a>`;
    ul.appendChild(li);
  };

  addPage(current - 1, "Previous", current === 1);

  // Page number buttons
  for (let i = 1; i <= pageCount; i++) {
    addPage(i, i, false, i === current);
  }

  // Next page button
  addPage(current + 1, "Next", current === pageCount);
}

document.addEventListener("DOMContentLoaded", () => {
  fetchFinishedAttempts(currentPage);
});

document.getElementById("pagination").addEventListener("click", (event) => {
  if (event.target && event.target.matches(".page-link")) {
    event.preventDefault();

    const newPage = parseInt(event.target.getAttribute("data-page"));
    if (!isNaN(newPage) && newPage !== currentPage) {
      fetchFinishedAttempts(newPage);
    }
  }
});
</script>
