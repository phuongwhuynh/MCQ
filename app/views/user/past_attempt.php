<?php
$attempt_id = isset($_GET['attempt_id']) ? $_GET['attempt_id'] : 1;
$current_page = isset($_GET['num_page']) ? $_GET['num_page'] : 1;
?>

<div class="container my-5">
  <h3 id="test-title" class="mb-4"></h3>

  <div id="question-container"></div>

  <a href="index.php?page=history&num_page=<?php echo $current_page; ?>" class="btn btn-primary mt-3">Back to history</a>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const attemptId = <?php echo json_encode($attempt_id); ?>;
  const currentPage = <?php echo json_encode($current_page); ?>;
  console.log(attemptId); 
  fetch('index.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      ajax: 1,
      controller: 'pastattempt',
      action: 'getPastAttempt',
      attempt_id: attemptId
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.error) return alert(data.error);

    renderTestReview(data);
  })
  .catch(err => console.error("Error loading past attempt:", err));
});

function renderTestReview(data) {
  const title = document.getElementById("test-title");
  const container = document.getElementById("question-container");
  container.innerHTML = "";

  const score = data.score;
  const total = data.total_questions;

  title.innerHTML = `Test Review: ${data.test_name} <span class="text-muted fs-5 ms-2">(${score}/${total})</span>`;
  data.questions.forEach((q, i) => {
    const choices = [q.ans1, q.ans2, q.ans3, q.ans4];
    const listItems = choices.map((choice, index) => {
      const choiceNumber = (index + 1).toString();  

      const isSelected = q.chosen_answer === choiceNumber;
      const isCorrect = q.correct_answer === choiceNumber;

      let iconHTML = "";
      if (isSelected) {
        iconHTML = isCorrect
          ? `<i class="bi bi-check-circle-fill text-success"></i>`
          : `<i class="bi bi-x-circle-fill text-danger"></i>`;
      }

      return `
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>
            <i class="bi bi-circle${isSelected ? '-fill' : ''} me-2"></i> ${choice}
          </span>
          ${iconHTML}
        </li>
      `;
    }).join(""); 

    const html = `
      <div class="mb-4">
        <h5>${i + 1}. ${q.description}</h5>
        <ul class="list-group">${listItems}</ul>
      </div>
    `;

    container.insertAdjacentHTML("beforeend", html);
  });
}
</script>
