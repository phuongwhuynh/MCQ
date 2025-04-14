<div class="container my-4">
  <!-- <h1 class="h3">Quiz Management</h1> -->
  <div>
    <section class="mb-5">
      <h2 class="h4 mb-3">Available Questions</h2>
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="row align-items-end mb-3">
            <!-- Search bar -->
            <div class="col-md-4 mb-2">
              <label for="search-input" class="form-label">Search</label>
              <input class="form-control" id="search-input" placeholder="Search questions..." type="text" />
            </div>

            <!-- Category Filter -->
            <div class="col-md-4 mb-2">
              <label for="category-filter" class="form-label">Filter by Category</label>
              <select class="form-select" id="category-filter" multiple="multiple">
                <option value="Math">Math</option>
                <option value="Literature">Literature</option>
                <option value="Science">Science</option>
                <option value="History">History</option>
                <option value="Geography">Geography</option>
              </select>
            </div>

            <!-- Sorting -->
            <div class="col-md-4 mb-2">
              <label for="sort-dropdown" class="form-label">Sort By</label>
              <select class="form-select" id="sort-dropdown">
                <option value="description_asc">Description (A-Z)</option>
                <option value="description_desc">Description (Z-A)</option>
                <option value="created_time_asc">Time (Oldest)</option>
                <option value="created_time_desc">Time (Newest)</option>
              </select>
            </div>
          </div>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-striped" id="questions-table">
              <thead>
                <tr>
                  <th scope="col">Description</th>
                  <th scope="col">Category</th>
                  <th scope="col">Image</th>
                </tr>
              </thead>
              <tbody id="questions-list">
                <!-- Dynamic rows inserted here by JavaScript -->
            </tbody>
            </table>
          </div>

          <!-- Pagination Controls -->
          <nav aria-label="Questions pagination">
            <ul class="pagination" id="pagination-controls"></ul>
          </nav>
        </div>
      </div>
    </section>

    <!-- Quiz Builder -->
    <section>
      <h2 class="h4 mb-3">Create New Quiz</h2>
      <div class="card shadow-sm">
        <div class="card-body">
          <form>
            <div class="mb-3">
              <label class="form-label" for="quiz-title">Quiz Title</label>
              <input class="form-control" id="quiz-title" type="text" required />
            </div>

            <div class="mb-3">
              <label class="form-label" for="quiz-time">Total Time (minutes)</label>
              <input class="form-control" id="quiz-time" type="number" required min="1" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="quiz-category">Category</label>
              <select class="form-select" id="quiz-category" required>
                <option value="" disabled selected>Select a Category</option>
                <option value="Math">Math</option>
                <option value="Literature">Literature</option>
                <option value="Science">Science</option>
                <option value="History">History</option>
                <option value="Geography">Geography</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label" for="quiz-questions">Selected Questions</label>
              <ul class="list-group" id="selected-questions">
                <!-- Dynamically added questions appear here -->
              </ul>
            </div>

            <div class="mb-3">
              <label class="form-label" for="quiz-image">Quiz Cover Image</label>
              <div class="custom-file-input-wrapper">
                <label for="quiz-image" class="btn btn-outline-primary">Upload Image</label>
                <input
                  class="form-control d-none"
                  type="file"
                  id="quiz-image"
                  name="image"
                  accept="image/*"
                  onchange="previewQuizImage(event)"
                />
                <small id="quiz-image-filename" class="text-muted d-block mt-1"></small>
              </div>
              <img id="quiz-image-preview" src="#" class="img-thumbnail mt-3 d-none" style="max-height: 200px;" />
            </div>

            <button class="btn btn-primary" type="submit">Create Quiz</button>
          </form>
        </div>
      </div>
    </section>
  </div>

  <!-- Modal for Question Details -->
  <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="questionModalLabel">Question Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>ID:</strong> <span id="modal-id"></span></p>
          <p><strong>Description:</strong> <span id="modal-description"></span></p>
          <p><strong>Category:</strong> <span id="modal-category"></span></p>
          <div id="modal-image"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const selectedQuestionMap = {}; 
function previewQuizImage(event) {
    const file = event.target.files[0];  // Get the first selected file
    const previewImage = document.getElementById('quiz-image-preview');
    const fileNameLabel = document.getElementById('quiz-image-filename');
    
    if (file) {
        const reader = new FileReader();
        
        // When file is read, update the preview image
        reader.onload = function(e) {
            // Display the preview image
            previewImage.src = e.target.result;
            previewImage.classList.remove('d-none');  // Show the image preview
            fileNameLabel.textContent = file.name;  // Update the file name label
        }
        
        // Read the file as a data URL to preview it
        reader.readAsDataURL(file);
    } else {
        // Reset preview if no file is selected
        previewImage.classList.add('d-none');
        fileNameLabel.textContent = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    let debounceTimeout;

    // Debounced fetchQuestions function
    function debouncedFetchQuestions() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(fetchQuestions, 300); // Delay of 300ms
    }

    // Add event listeners
    document.getElementById('search-input').addEventListener('input', function () {
        debouncedFetchQuestions();
    });

    $('#category-filter').select2({
        placeholder: "Select categories",
        allowClear: true,
        width: '100%' 
    }).on('change', function () {
        fetchQuestions();  
    });

    document.getElementById('sort-dropdown').addEventListener('change', function () {
        fetchQuestions();
    });

    // Call fetchQuestions initially to load questions
    fetchQuestions();
});


function fetchQuestions(page = 1) {
    currentQuestionPage = page;
    const questionSort = document.getElementById('sort-dropdown').value;

    const selectedCategories = $('#category-filter').val() || [];
    const questionSearch = document.getElementById('search-input').value;
    console.log(questionSearch)
    const queryParams = new URLSearchParams({
        pageNum: page,
        sort: questionSort,
        categories: selectedCategories.join(','),
        search: questionSearch
    });
    fetch(`index.php?controller=test&ajax=1&action=handlePagination&${queryParams.toString()}`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {

        const { questions, totalPages } = data;

        const container = document.getElementById('questions-list');

        container.innerHTML = '';
        console.log("wtf")

        if (questions.length === 0) {
            container.innerHTML = "<p>No questions found.</p>";
            renderQuestionPagination(totalPages);
            return;
        }

        questions.forEach(question => {
            const modalId = `question-modal-${question.question_id}`;

            // Create the table row element
            const row = document.createElement('tr');

            // Set the inner HTML for the table row
            row.innerHTML = `
                <td class="description-cell" data-bs-toggle="modal" data-bs-target="#${modalId}">
                    ${question.description}
                </td>
                <td>${question.cate}</td>
                <td>
                    ${question.image_path ? `
                        <img src="public/${question.image_path}" class="img-responsive" style="max-height: 40px" alt="Question Image"/>
                    ` : ''}
                </td>
                <td class="text-center">
                    <input class="form-check-input" type="checkbox" data-question-id="${question.question_id}" ${question.is_selected ? 'checked' : ''} />
                </td>
            `;

            // Add event listener for checkbox logic
            const checkbox = row.querySelector('.form-check-input');
            checkbox.addEventListener('change', (event) => {
                const questionId = question.question_id;
                const description = question.description;
                const isSelected = event.target.checked;

                if (isSelected) {
                    // Cache and render in selected list
                    selectedQuestionMap[questionId] = { questionId, description };
                    renderSelectedQuestions();
                } else {
                    // Remove from cache and re-render
                    delete selectedQuestionMap[questionId];
                    renderSelectedQuestions();
                }
            });

            // Find the table body to insert the row into
            const questionsList = document.getElementById('questions-list');
            questionsList.appendChild(row);

            // Create the modal
            const modal = document.createElement('div');
            modal.classList.add('modal', 'fade');
            modal.id = modalId;
            modal.tabIndex = -1;
            modal.setAttribute('aria-labelledby', `${modalId}-label`);
            modal.setAttribute('aria-hidden', 'true');
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="${modalId}-label">Question Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Description:</strong> ${question.description}</p>
                            <p><strong>Category:</strong> ${question.cate}</p>
                            <p><strong>Answer 1:</strong> ${question.ans1}</p>
                            <p><strong>Answer 2:</strong> ${question.ans2}</p>
                            <p><strong>Answer 3:</strong> ${question.ans3}</p>
                            <p><strong>Answer 4:</strong> ${question.ans4}</p>
                            <p><strong>Correct Answer:</strong> ${question.correct_answer}</p>
                            ${question.image_path ? `<img src="public/${question.image_path}" class="img-fluid mt-2" alt="Question Image">` : ''}
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            `;

            // Append the modal to the body (outside the table)
            document.body.appendChild(modal);
        });


        renderQuestionPagination(totalPages);
    })
    .catch(error => {
        console.error("Error loading questions:", error);
    });
}

function renderQuestionPagination(totalPages) {
  const paginationContainer = document.getElementById('pagination-controls');
  paginationContainer.innerHTML = '';

  const createPageItem = (page, isActive = false) => {
    const li = document.createElement('li');
    li.className = `page-item ${isActive ? 'active' : ''}`;
    li.innerHTML = `<a class="page-link" href="#">${page}</a>`;
    li.addEventListener('click', (e) => {
      e.preventDefault();
      if (!isActive) fetchQuestions(page);
    });
    return li;
  };

  if (totalPages <= 1) return;

  for (let i = 1; i <= totalPages; i++) {
    paginationContainer.appendChild(createPageItem(i, i === currentQuestionPage));
  }
}
function renderSelectedQuestions() {
    const selectedList = document.getElementById('selected-questions');
    selectedList.innerHTML = '';  // Clear the existing list

    let counter = 1;  // Initialize the counter to 1

    for (const id in selectedQuestionMap) {
        const { description } = selectedQuestionMap[id];

        const li = document.createElement('li');
        li.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
        li.dataset.questionId = id;  // Keep the question ID for future reference
        li.innerHTML = `
            <span>Question ${counter}: ${description}</span>  <!-- Use counter instead of id -->
            <button class="btn btn-sm btn-outline-danger" data-question-id="${id}">Remove</button>
        `;

        li.querySelector('button').addEventListener('click', function () {
            const questionId = this.getAttribute('data-question-id');
            delete selectedQuestionMap[questionId];  // Remove from the selected questions map
            renderSelectedQuestions();  // Re-render the selected questions

            // Also uncheck the checkbox in the table
            const checkbox = document.querySelector(`input[data-question-id="${questionId}"]`);
            if (checkbox) checkbox.checked = false;
        });

        selectedList.appendChild(li);
        counter++;  // Increment the counter for the next question
    }
}


document.querySelector('form').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent form from submitting the default way

  // Collect form data
  const quizTitle = document.getElementById('quiz-title').value;
  const quizTime = document.getElementById('quiz-time').value;
  const quizCategory = document.getElementById('quiz-category').value; // Get selected category
  const quizImage = document.getElementById('quiz-image').files[0];
  const selectedQuestions = Array.from(document.querySelectorAll('#selected-questions .list-group-item')).map(item => item.dataset.questionId);
  
  console.log(selectedQuestions);

  // Validation checks
  if (!quizTitle || !quizTime || !quizCategory || selectedQuestions.length === 0) {
    alert('Please fill in all required fields, select a category, and select questions.');
    return;
  }

  // Prepare form data for submission
  const formData = new FormData();
  formData.append('test_name', quizTitle);
  formData.append('total_time', quizTime);
  formData.append('category', quizCategory); // Add category to form data
  formData.append('question_ids', JSON.stringify(selectedQuestions)); 
  formData.append('ajax', 1);
  formData.append('controller', 'test');
  formData.append('action', 'submitTest');

  if (quizImage) {
    console.log("Image selected")
    formData.append('image', quizImage); // Attach the image if selected
  }
  console.log(quizImage);

  // Send the data to the server using Fetch API
  fetch('index.php', {
    method: 'POST',
    body: formData,
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Quiz created successfully!');
      // Optionally, clear the form or redirect
      clearForm();
    } else {
      alert('Error creating quiz: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('An error occurred while creating the quiz.');
  });
});



function clearForm() {
  // Clear form fields
  document.getElementById('quiz-title').value = '';
  document.getElementById('quiz-time').value = '';
  
  // Clear file input and image preview
  document.getElementById('quiz-image').value = '';
  document.getElementById('quiz-image-preview').classList.add('d-none');
  document.getElementById('quiz-image-filename').textContent = '';

  // Clear selected questions
  document.getElementById('selected-questions').innerHTML = '';
}



</script>
