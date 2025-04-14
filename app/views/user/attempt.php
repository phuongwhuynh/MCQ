<div class="container py-4">
    <!-- Time Remaining Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="font-weight-bold p-3 border rounded" style="background-color: #f8f9fa; text-align: center; white-space: nowrap;">
            Time Remaining: <span id="timer">Loading...</span>
        </div>
        <div class="w-50">
            <div class="progress" style="height: 25px;">
                <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Question Container -->
    <div id="question-container" class="mb-4 border shadow-sm p-4 rounded" style="background-color: #f8f9fa;">
        <h5 id="question-text" class="font-weight-bold text-dark">Loading question...</h5>
        <!-- Centered Image -->
        <img id="question-image" class="img-fluid my-3 d-none rounded mx-auto d-block" style="max-width: 100%; max-height: 300px; object-fit: contain;" />
        <!-- Answers Section -->
        <div id="answers" class="list-group shadow-sm rounded"></div>
    </div>

    <!-- Next Button -->
    <button class="btn btn-primary btn-block" id="next-button" disabled>Next Question</button>
</div>





<div id="attempt-status"></div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const attemptId = urlParams.get("attempt_id");
    let remainingTime = 0;
    let totalQuestions = 1;
    let currentQuestion = 0;
    let selectedAnswer = null;

    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
        const secs = (seconds % 60).toString().padStart(2, '0');
        return `${mins}:${secs}`;
    }

    function updateTimer() {
        if (remainingTime <= 0) {
            alert("Time's up!");
            window.location.href = "index.php?page=history";
            return;
        }
        document.getElementById('timer').textContent = formatTime(remainingTime);
        remainingTime--;
    }

    function loadAttempt() {
        fetch(`index.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ attempt_id: attemptId, ajax:1, action:"getAttempt", controller: "test" })
        })
        .then(res => res.json())
        .then(data => {
            if (data.finished === true) {
                document.getElementById('attempt-status').textContent = "Attempt not found or already finished.";
                document.getElementById('attempt-status').classList.add('alert', 'alert-danger');
                
                setTimeout(() => {
                    window.location.href = 'index.php?page=home'; 
                }, 2000); 
                
                return;
            }


            remainingTime = data.remaining_time;
            currentQuestion = data.current_question;
            loadQuestion(data.question);
            totalQuestions = data.total_questions;
            updateProgress();

        }) 
        .catch(error => {
        console.error('Error fetching attempt:', error);
        
        // Display an error message
        document.getElementById('attempt-status').textContent = "Error fetching attempt.";
        document.getElementById('attempt-status').classList.add('alert', 'alert-danger');
        
        // Redirect user to the home page after displaying the error message
        setTimeout(() => {
            window.location.href = 'index.php?page=home'; // Redirect to the home page
        }, 3000); // Delay the redirect by 3 seconds to give time for the user to see the message
    });

        
    }

    function loadQuestion(q) {
        document.getElementById('question-text').textContent = q.description;
        const img = document.getElementById('question-image');
        if (q.image_path) {
            img.src = "public/"+q.image_path;
            img.classList.remove('d-none');
        } else {
            img.classList.add('d-none');
        }

        const answersDiv = document.getElementById('answers');
        answersDiv.innerHTML = '';
        ['ans1', 'ans2', 'ans3', 'ans4'].forEach((key, idx) => {
            const btn = document.createElement('button');
            btn.className = 'list-group-item list-group-item-action';
            btn.textContent = q[key];
            btn.onclick = () => {
                selectedAnswer = (idx + 1).toString();
                document.querySelectorAll('#answers button').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('next-button').disabled = false;
            };
            answersDiv.appendChild(btn);
        });
    }

    function updateProgress() {
        const percent = Math.round(((currentQuestion-1) / totalQuestions) * 100);
        const bar = document.getElementById('progress-bar');
        bar.style.width = percent + '%';
        bar.textContent = `${percent}%`;
    }

    document.getElementById('next-button').addEventListener('click', () => {
        if (!selectedAnswer) return;

        fetch(`index.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ attempt_id: attemptId, chosen_answer: selectedAnswer,ajax:1,controller:"test", action: "submitAnswer" })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            if (data && data.finished) {
                    // Show modal with result
                    const modalContent = `
                        <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="resultModalLabel">Test Completed</h5>
                            </div>
                            <div class="modal-body text-center">
                                <p><strong>Correct Answers:</strong> ${data.correct_answers} / ${data.total_questions}</p>
                            </div>
                            <a href="index.php?page=home" class="btn btn-primary">Return to home page</a>
                            </div>
                        </div>
                        </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', modalContent);
                    const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                    resultModal.show();
            } else if (data && data.success) {
                selectedAnswer = null;
                document.getElementById('next-button').disabled = true;
                remainingTime = data.remaining_time;
                currentQuestion = data.current_question;
                loadQuestion(data.question);
                updateProgress();
            } 
            else {
                alert(data.message)
            }
        });
    });

    loadAttempt();
    setInterval(updateTimer, 1000);
</script>
