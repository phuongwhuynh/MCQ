<div class="container my-4">
  <h3 class="mb-4">
    <i class="bi bi-hourglass-split me-2 text-warning"></i>Current In-Attempt Tests
  </h3>

  <div class="list-group">
    <!-- <a href="index.php?page=attempt&id=123" class="list-group-item list-group-item-action d-flex align-items-start gap-3 py-3 shadow-sm text-decoration-none text-dark">
      <img src="https://picsum.photos/100/70" alt="Test Image" class="rounded" style="width: 100px; height: 70px; object-fit: cover;">
      <div class="flex-grow-1">
        <h5 class="mb-1">Math Final Exam</h5>
        <p class="mb-0 text-muted">Remaining time: <span class="fw-semibold">xx minutes</span></p>
      </div>
    </a>

    <a href="index.php?page=attempt&id=124" class="list-group-item list-group-item-action d-flex align-items-start gap-3 py-3 shadow-sm text-decoration-none text-dark">
      <img src="https://picsum.photos/100/70?2" alt="Test Image" class="rounded" style="width: 100px; height: 70px; object-fit: cover;">
      <div class="flex-grow-1">
        <h5 class="mb-1">Physics Midterm</h5>
        <p class="mb-0 text-muted">Remaining time: <span class="fw-semibold">xx minutes</span></p>
      </div>
    </a> -->

  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    fetch('index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            ajax: 1,
            controller: 'dashboard',
            action: 'getDashboard'
        })
    })
    .then(res => res.json())
    .then(data => {
        const listGroup = document.querySelector('.list-group');
        listGroup.innerHTML = ''; // Clear dummy content

        if (!data.length) {
            listGroup.innerHTML = `<div class="text-muted px-3 py-2">No ongoing tests found.</div>`;
            return;
        }

        data.forEach(attempt => {
            const link = document.createElement('a');
            link.href = `index.php?page=attempt&attempt_id=${attempt.attempt_id}`;
            link.className = 'list-group-item list-group-item-action d-flex align-items-start gap-3 py-3 shadow-sm text-decoration-none text-dark';

            link.innerHTML = `
                <img src="public/${attempt.image_path || 'images/tests/star.png'}" alt="Test Image"
                     class="rounded" style="width: 100px; height: 70px; object-fit: cover;">
                <div class="flex-grow-1">
                    <h5 class="mb-1">${attempt.test_name}</h5>
                    <p class="mb-0 text-muted">Remaining time: <span class="fw-semibold">${Math.ceil(attempt.remaining_time / 60)} minutes</span></p>
                </div>
            `;

            listGroup.appendChild(link);
        });
    })
    .catch(err => {
        console.error('Error loading attempts:', err);
    });
});
</script>
