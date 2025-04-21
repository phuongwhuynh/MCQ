<?php
$returnPage = isset($_GET['return_page']) ? (int)$_GET['return_page'] : 1;
$testId = isset($_GET['id']) ? (int)$_GET['id'] : null;

?>

<div class="container main-content my-5 border border-2 p-4">
      <div class="d-flex flex-row justify-content-between border-bottom pb-2 mb-3">
        <div class="">
          <h6>Test 1</h6>
          <p class="m-0">10 question</p>
        </div>
        <div class="p-2">
        <a href="/MCQ/resources/<?= $returnPage ?>" 
          class="btn btn-outline-light text-dark border border-2" 
          id="backToResourceBtn">
          Back to resource
        </a>
        <button
          type="button"
          class="btn btn-outline-primary border border-2"
          onclick="startAttempt()"
        >
          Play
        </button>
        </div>
      </div>
      
      <div class="container main-content my-5 border border-2 p-4" id="question-container">
        <!-- Questions will be rendered here -->
      </div>



      
</div>
<script>
    const testId = <?= $testId !== null ? $testId : 'null' ?>;
</script>

<script>
// document.addEventListener("DOMContentLoaded", () => {
//   const params = new URLSearchParams(window.location.search);
//   const returnPage = params.get("return_page") || 1;

//   const backBtn = document.getElementById("backToResourceBtn");
//   if (backBtn) {
//     backBtn.addEventListener("click", () => {
//       window.location.href = `index.php?page=resources&currentPage=${returnPage}`;
//     });
//   }
// });
document.addEventListener("DOMContentLoaded", () => {
  console.log(testId)
  if (testId) {
    fetchQuestions(testId);
  }

  function fetchQuestions(testId) {
    fetch('index.php', {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        ajax: 1,
        controller: 'test',
        action: 'getPreviewQuestions',
        test_id: testId
      })
    })
      .then(res => res.json())
      .then(data => {
        if (!data) return;

        // Update test name and question count
        const testNameEl = document.querySelector(".main-content h6");
        const questionCountEl = document.querySelector(".main-content p");

        if (testNameEl && questionCountEl) {
          testNameEl.textContent = data.test_name;
          questionCountEl.textContent = `${data.number_of_questions} questions`;
        }

        // Render questions
        renderQuestions(data.questions);
      })
      .catch(err => console.error("Failed to fetch questions:", err));
  }
  function renderQuestions(questions) {
    const container = document.getElementById("question-container");
    container.innerHTML = "";

    if (!questions.length) {
      container.innerHTML = "<p>No questions available.</p>";
      return;
    }

    questions.forEach((q, index) => {
      const hasImage = !!q.imagePath;
      const imageHtml = hasImage
        ? `<div class="mb-3" style="height: 150px; overflow: hidden;">
            <img src="/MCQ/public/${q.imagePath}" alt="question image" style="width: 100%; height: 100%; object-fit: contain;">
          </div>`
        : "";

      const html = `
        <div class="question">
          ${imageHtml}

          <p class="my-3">
            <b>${q.question_number} ${q.description}</b>
          </p>

          <div class="row mx-0 my-2">
            <div class="form-check col">
              <input type="radio" class="form-check-input" name="question${index}" value="1" id="q${index}a1">
              <label class="form-check-label" for="q${index}a1">${q.ans1}</label>
            </div>
            <div class="form-check col">
              <input type="radio" class="form-check-input" name="question${index}" value="2" id="q${index}a2">
              <label class="form-check-label" for="q${index}a2">${q.ans2}</label>
            </div>
          </div>

          <div class="row mx-0 my-2">
            <div class="form-check col">
              <input type="radio" class="form-check-input" name="question${index}" value="3" id="q${index}a3">
              <label class="form-check-label" for="q${index}a3">${q.ans3}</label>
            </div>
            <div class="form-check col">
              <input type="radio" class="form-check-input" name="question${index}" value="4" id="q${index}a4">
              <label class="form-check-label" for="q${index}a4">${q.ans4}</label>
            </div>
          </div>

          <hr>
        </div>
      `;

      container.insertAdjacentHTML("beforeend", html);
    });  }
});
function startAttempt() {
  fetch('index.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
      ajax: 1,
      controller: 'test',
      action: 'createAttempt',
      test_id: testId
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'Unauthorized') {
      alert('You must be logged in to attempt a test.');
    } else if (data.status === 'success') {
      window.location.href = `/MCQ/attempt/${data.attempt_id}`;
    } else {
      console.error('Unexpected response:', data);
    }
  })
  .catch(err => console.error("Failed to initiate attempt:", err));
}
</script>
