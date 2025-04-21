<div class="container my-4">
    <section class="mb-5 mt-4"> 
        <h2 class="h4 mb-3">Question Bank</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <a href="/MCQ/addquestion" class="btn btn-primary">
                        <i class="fas fa-plus-circle fa-lg me-2"></i> Add Question
                    </a>
                </div>
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
    fetch(`index.php?controller=question&ajax=1&action=handlePagination&${queryParams.toString()}`, {
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
                <td>
                    <button class="btn btn-danger btn-sm" onclick="deleteQuestion(${question.question_id}, this)">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </td>

            `;

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


        renderQuestionPagination(page, totalPages);
    })
    .catch(error => {
        console.error("Error loading questions:", error);
    });
}
function renderQuestionPagination(currentPage, totalPages) {
  const paginationContainer = document.getElementById('pagination-controls');
  paginationContainer.innerHTML = '';  

  const createPageItem = (page, label, isActive = false, isDisabled = false) => {
    const li = document.createElement('li');
    li.className = `page-item ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
    li.innerHTML = `<a class="page-link" href="#">${label}</a>`;
    li.addEventListener('click', (e) => {
      e.preventDefault();
      if (!isDisabled && !isActive) fetchQuestions(page);  
    });
    return li;
  };

  paginationContainer.appendChild(createPageItem(currentPage - 1, 'Previous', false, currentPage === 1));

  if (totalPages < 5) {
    for (let i = 1; i <= totalPages; i++) {
      paginationContainer.appendChild(createPageItem(i, i, i === currentPage));
    }
  } else {
    if (currentPage > 3) {
      paginationContainer.appendChild(createPageItem(1, 1));
      paginationContainer.appendChild(createPageItem(0, '...', true));  
    }

    const startPage = Math.max(currentPage - 2, 2);  
    const endPage = Math.min(currentPage + 2, totalPages - 1);  

    for (let i = startPage; i <= endPage; i++) {
      paginationContainer.appendChild(createPageItem(i, i, i === currentPage));
    }

    if (currentPage < totalPages - 2) {
      paginationContainer.appendChild(createPageItem(0, '...', true)); 
      paginationContainer.appendChild(createPageItem(totalPages, totalPages));
    }
  }

  paginationContainer.appendChild(createPageItem(currentPage + 1, 'Next', false, currentPage === totalPages));
}

function deleteQuestion(question_id, button) {
    if (confirm("Are you sure you want to delete this question?")) {
        fetch('index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                controller: 'question',
                ajax: 1,
                action: 'deleteQuestion',
                question_id: question_id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = button.closest('tr');
                row.remove();
                alert("Question deleted successfully!");
            } else {
                alert("Error deleting question.");
            }
        })
        .catch(error => {
            console.error("Error deleting question:", error);
            alert("There was an error deleting the question.");
        });
    }
}


// function renderQuestionPagination(totalPages) {
//   const paginationContainer = document.getElementById('pagination-controls');
//   paginationContainer.innerHTML = '';

//   const createPageItem = (page, isActive = false) => {
//     const li = document.createElement('li');
//     li.className = `page-item ${isActive ? 'active' : ''}`;
//     li.innerHTML = `<a class="page-link" href="#">${page}</a>`;
//     li.addEventListener('click', (e) => {
//       e.preventDefault();
//       if (!isActive) fetchQuestions(page);
//     });
//     return li;
//   };

//   if (totalPages <= 1) return;

//   for (let i = 1; i <= totalPages; i++) {
//     paginationContainer.appendChild(createPageItem(i, i === currentQuestionPage));
//   }
// }

</script>