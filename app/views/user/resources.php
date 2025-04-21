
<div class="container">
  <h1 class="fw-bold text-center my-5">Explore Our Exam</h1>
  <div class="container-fluid m-0">
    <form class="d-flex" action="">
        <input class="form-control" id="search-input" placeholder="Search tests..." type="text" />
    </form>

    <div class="d-flex flex-row align-items-end justify-content-between my-3 gap-3 flex-wrap">
      <div style="min-width: 300px; max-width: 100%;">
        <label for="category-filter" class="form-label">Filter by Category</label>
        <select class="form-select" id="category-filter" multiple="multiple">
          <option value="Math">Math</option>
          <option value="Literature">Literature</option>
          <option value="Science">Science</option>
          <option value="History">History</option>
          <option value="Geography">Geography</option>
        </select>
      </div>

      <div style="width: 200px; flex-shrink: 0;">
        <label for="sort-dropdown" class="form-label">Sort By</label>
        <select class="form-select" id="sort-dropdown">
          <option value="total_attempts_desc">Relevance</option>
          <option value="title_asc">Title (A-Z)</option>
          <option value="title_desc">Title (Z-A)</option>
          <option value="created_time_asc">Time (Oldest)</option>
          <option value="created_time_desc">Time (Newest)</option>
        </select>
      </div>
    </div>
  </div>

  <div class="row1 row my-5"></div>
</div>

<div class="my-5">
  <nav>
  <ul class="pagination justify-content-center" id="pagination"></ul>
  </nav>
</div>

<script>
  <?php
  $initialPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
  ?>  
  const initialPage = <?= $initialPage ?>;
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  $('#category-filter').select2({
    placeholder: "Select categories",
    allowClear: true,
    width: '100%'
  });

  $('#category-filter').on('change', function() {
    fetchTest(1);
  });

  document.getElementById("search-input").addEventListener("input", debounce(() => fetchTest(1), 300));
  document.getElementById("sort-dropdown").addEventListener("change", () => fetchTest(1));

  // Initial load

  fetchTest(initialPage); 
});

function fetchTest(page = 1) {
  const searchTerm = document.getElementById("search-input").value;
  const selectedCategories = $('#category-filter').val() || [];
  const sort = document.getElementById("sort-dropdown").value;

  const params = new URLSearchParams({
    page,
    search: searchTerm,
    sort,
    categories: selectedCategories.join(",")
  });

  fetch(`index.php?ajax=1&controller=resources&action=fetchTest&${params}`)
    .then(response => response.json())
    .then(data => {
      renderTests(data.tests, page);
      renderPagination(data.totalPages, page);
    })
    .catch(err => console.error("Failed to fetch tests:", err));
}

function renderTests(tests, page) {
  const container = document.querySelector(".row1");

  if (!tests.length) {
    container.innerHTML = '<p>No tests available at the moment.</p>';
    return;
  }

  const template = (test) => {
    const imagePath = test.image_path ? `/MCQ/public/${test.image_path}` : '/MCQ/public/images/tests/star.png';
    return `
      <div class="col-md-6 col-lg-3 mb-4">
        <a class="text-dark text-decoration-none" href="/MCQ/preview/${test.test_id}/${page}">
          <div class="card">
            <div style="height: 150px; overflow: hidden;">
              <img class="card-img-top" src="${imagePath}" alt="Test Image" style="height: 100%; width: 100%; object-fit: cover;">
            </div>
            <div class="card-body">
              <h4 class="card-title">${test.test_name}</h4>
            </div>
          </div>
        </a>
      </div>
    `;
  };

  container.innerHTML = tests.map(template).join("");
}

// function renderPagination(totalPages, currentPage) {
//   const container = document.querySelector(".pagination");
//   if (!container) return;

//   let html = '';

//   html += `<a href="#" onclick="fetchTest(${Math.max(1, currentPage - 1)}); return false;">&laquo;</a>`;

//   for (let i = 1; i <= totalPages; i++) {
//     html += `<a href="#" class="${i === currentPage ? 'active' : ''}" onclick="fetchTest(${i}); return false;">${i}</a>`;
//   }

//   html += `<a href="#" onclick="fetchTest(${Math.min(totalPages, currentPage + 1)}); return false;">&raquo;</a>`;

//   container.innerHTML = html;
// }
function renderPagination(totalPages, currentPage) {
  const paginationContainer = document.getElementById('pagination');
  if (!paginationContainer) return;

  paginationContainer.innerHTML = ''; // clear old <li>

  const createPageItem = (page, label, isActive = false, isDisabled = false) => {
    const li = document.createElement('li');
    li.className = `page-item ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
    li.innerHTML = `<a class="page-link" href="#">${label}</a>`;
    li.querySelector('a').addEventListener('click', (e) => {
      e.preventDefault();
      if (!isDisabled && !isActive) fetchTest(page); // or fetchQuestions(page)
    });
    return li;
  };

  paginationContainer.appendChild(createPageItem(currentPage - 1, '«', false, currentPage === 1));

  if (totalPages < 5) {
    for (let i = 1; i <= totalPages; i++) {
      paginationContainer.appendChild(createPageItem(i, i, i === currentPage));
    }
  } else {
    if (currentPage > 3) {
      paginationContainer.appendChild(createPageItem(1, 1));
      paginationContainer.appendChild(createPageItem(0, '...', true, true));
    }

    const startPage = Math.max(2, currentPage - 2);
    const endPage = Math.min(totalPages - 1, currentPage + 2);

    for (let i = startPage; i <= endPage; i++) {
      paginationContainer.appendChild(createPageItem(i, i, i === currentPage));
    }

    if (currentPage < totalPages - 2) {
      paginationContainer.appendChild(createPageItem(0, '...', true, true));
      paginationContainer.appendChild(createPageItem(totalPages, totalPages));
    }
  }

  paginationContainer.appendChild(createPageItem(currentPage + 1, '»', false, currentPage === totalPages));
}



function debounce(func, delay) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  };
}
</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
