<section class="mb-5">
  <h2 class="h4 mb-3">Manage Tests</h2>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row align-items-end mb-3">
        <!-- Search bar -->
        <div class="col-md-4 mb-2">
          <label for="search-input" class="form-label">Search</label>
          <input class="form-control" id="search-input" placeholder="Search tests..." type="text" />
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
        <table class="table table-striped" id="tests-table">
          <thead>
            <tr>
              <th scope="col">Test Title</th>
              <th scope="col">Category</th>
              <th scope="col">Created Time</th>
              <th scope="col">Image</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody id="tests-list">
            <!-- Dynamic rows inserted here by JavaScript -->
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls -->
      <nav aria-label="Tests pagination">
        <ul class="pagination" id="pagination-controls"></ul>
      </nav>
    </div>
  </div>
</section>

<div class="modal fade" id="questionsModal" tabindex="-1" aria-labelledby="questionsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="questionsModalLabel">Test Questions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Description</th>
              <th>Options</th>
              <th>Image</th>
            </tr>
          </thead>
          <tbody id="questionsModalBody">
            <!-- Filled by JS -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<script>
let currentPage = 1;
const searchInput = document.getElementById("search-input");
const categoryFilter = document.getElementById("category-filter");
const sortDropdown = document.getElementById("sort-dropdown");
const testsList = document.getElementById("tests-list");
const paginationControls = document.getElementById("pagination-controls");
function getSelectedCategories() {
  return Array.from(categoryFilter.selectedOptions).map(opt => opt.value);
}
function changeStatus(event) {
  const button = event.target;
  const testId = button.dataset.id;
  const currentStatus = button.dataset.status;
  const toggleaction = currentStatus==='private'? 'publish' : 'privatize'

  fetch(`index.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      test_id: testId,
      controller: "test",
      ajax: 1,
      action: toggleaction
    }),
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        fetchTests(1)
      } else {
        alert('Failed to change the status');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while updating the status.');
    });
}
function deleteTest(event) {
  const button = event.target;
  const testId = button.dataset.id;
  const currentStatus = button.dataset.status;

  fetch('index.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      test_id: testId,
      controller: "test",
      ajax: 1,
      action: "delete"
    }),
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        fetchTests(1)
      } else {
        alert('Failed to change the status');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while updating the status.');
    });
}
function fetchTests(page = 1) {
  currentPage = page;

  const testSort = document.getElementById('sort-dropdown').value;
  const selectedCategories = $('#category-filter').val() || [];
  const testSearch = document.getElementById('search-input').value;

  const queryParams = new URLSearchParams({
      action: 'fetchTest',
      pageNum: page,
      sort: testSort,
      categories: selectedCategories.join(','),
      search: testSearch
  });

  fetch(`index.php?controller=test&ajax=1&${queryParams.toString()}`, {
      method: 'GET'
  })
  .then(res => res.json())
  .then(data => {
      const { tests, totalPages } = data;

      const container = document.getElementById('tests-list');
      const pagination = document.getElementById('pagination-controls');

      container.innerHTML = '';

      if (data.success && tests.length > 0) {
          tests.forEach(test => {
              const row = document.createElement("tr");
              row.innerHTML = `
                  <td>${test.test_name || "(Untitled)"}</td>
                  <td>${test.test_category || "-"}</td>
                  <td>${new Date(test.created_time).toLocaleDateString()}</td>
                  <td>${test.image_path ? `<img src="public/${test.image_path}" width="50">` : "-"}</td>
                  <td>${test.status}</td>
                  <td>
                    <button class="btn btn-sm btn-info view-questions" data-id="${test.test_id}">
                      View Questions
                    </button>
                    <button class="btn btn-sm btn-warning toggle-status" data-id="${test.test_id}" data-status="${test.status}" onclick="changeStatus(event)">
                      ${test.status === 'public' ? 'Privatize' : 'Publish'}
                    </button>
                    <button class="btn btn-sm btn-danger delete-test" data-id="${test.test_id}" onclick="deleteTest(event)">
                      Delete
                    </button>
                  </td>

              `;
              container.appendChild(row);
          });

          // Attach modal show behavior
          document.querySelectorAll('.view-questions').forEach(button => {
            button.addEventListener('click', function () {
                const testId = this.dataset.id;
                
                // Fetch questions using AJAX
                fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        test_id: testId,
                        controller: 'test',
                        action: 'getQuestions',
                        ajax: 1,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.getElementById('questionsModalBody');
                    modalBody.innerHTML = ''; // Clear any existing data in the modal

                    if (data && data.length > 0) {
                        // Loop through the questions and add them to the modal body
                        data.forEach((question, index) => {
                            const row = document.createElement('tr');
                            const options = [question.ans1, question.ans2, question.ans3, question.ans4];
                            
                            // Mark the correct answer with a green tick
                            const optionsHtml = options.map((option, idx) => {
                                const isCorrect = question.correct_answer == (idx + 1);
                                return `<li>${option} ${isCorrect ? '<span style="color: green;">✔</span>' : ''}</li>`;
                            }).join('');
                            
                            row.innerHTML = `
                                <td>${question.question_number}</td>
                                <td>${question.description}</td>
                                <td>
                                    <ul>
                                        ${optionsHtml}
                                    </ul>
                                </td>
                                <td>${question.imagePath ? `<img src="public/${question.imagePath}" width="50">` : '-'}</td>
                            `;
                            modalBody.appendChild(row);
                        });
                    } else {
                        modalBody.innerHTML = '<tr><td colspan="4" class="text-center">No questions found.</td></tr>';
                    }

                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('questionsModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching questions:', error);
                    alert('Failed to load questions.');
                });
            });
        });



          // Render pagination
          pagination.innerHTML = '';
          for (let i = 1; i <= totalPages; i++) {
              const li = document.createElement('li');
              li.className = `page-item ${i === page ? 'active' : ''}`;
              li.innerHTML = `<button class="page-link">${i}</button>`;
              li.querySelector('button').addEventListener('click', () => fetchTests(i));
              pagination.appendChild(li);
          }

      } else {
          container.innerHTML = `<tr><td colspan="6" class="text-center">${data.message || "No tests found."}</td></tr>`;
          pagination.innerHTML = '';
      }
  });
}
document.addEventListener("DOMContentLoaded", function () {

  $('#category-filter').select2({
    placeholder: "Select categories",
    allowClear: true,
    width: '100%'
  });

  $('#category-filter').on('change', function () {
    fetchTests(1); // Reset to page 1 on change
  });






  // Debounce search input
  let searchTimeout;
  searchInput.addEventListener("input", () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      currentPage = 1;
      fetchTests();
    }, 400);
  });

  categoryFilter.addEventListener("change", () => {
    currentPage = 1;
    fetchTests();
  });

  sortDropdown.addEventListener("change", () => {
    currentPage = 1;
    fetchTests();
  });

  // Initial fetch
  fetchTests();
});


</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
