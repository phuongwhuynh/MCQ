<div class="container my-4">
    <header class="d-flex justify-content-between align-items-center py-3">
    <h1 class="h3">Quiz Management</h1>
    <nav>
        <ul class="nav">
        <li class="nav-item">
            <a class="nav-link text-primary" href="#"> Home </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-primary" href="#"> Questions </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-primary" href="#"> Create Quiz </a>
        </li>
        </ul>
    </nav>
    </header>
    <main>
    <section class="mb-5">
        <h2 class="h4 mb-3">Available Questions</h2>
        <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            <input
                class="form-control w-50"
                id="search-input"
                placeholder="Search questions..."
                type="text"
            />
            <div>
                <button class="btn btn-primary me-2" id="sort-id">
                Sort by ID
                </button>
                <button class="btn btn-primary" id="sort-description">
                Sort by Description
                </button>
            </div>
            </div>
            <div class="table-responsive">
            <table class="table table-striped" id="questions-table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Description</th>
                    <th scope="col">Answer 1</th>
                    <th scope="col">Answer 2</th>
                    <th scope="col">Answer 3</th>
                    <th scope="col">Answer 4</th>
                    <th scope="col">Correct Answer</th>
                    <th scope="col">Image</th>
                    <th scope="col">Select</th>
                </tr>
                </thead>
                <tbody>
                <!-- -------------------------------------Demo data----------------------------------------------- -->
                <tr>
                    <td>1</td>
                    <td
                    class="description-cell"
                    data-bs-toggle="modal"
                    data-bs-target="#questionModal"
                    >
                    Even if you feel bad Even if you wish to leave and go
                    away In and out of madness In and out of goodness You
                    must be awake When it's freezin', when it's burnin' Hold
                    your black car, let it go far Burns the fever burns as
                    fire Never never surrender Max power maximum and higher
                    Beat'em all, let's beat'em all! Max power, get it on,
                    you go there Let's get what you belong to Max power
                    maximum and higher Beat'em all, let's beat'em all! Max
                    power, get it on, you go there Let's get what you belong
                    to Always in the riot Always in the lion's cage It's
                    what you need
                    </td>
                    <td class="description-cell">
                    Beat'em all, let's beat'em all! Max power Beat'em all,
                    let's beat'em all! Max powerBeat'em all, let's beat'em
                    all! Max powerBeat'em all, let's beat'em all! Max power
                    </td>
                    <td class="description-cell">
                    Beat'em all, let's beat'em all! Max power
                    </td>
                    <td class="description-cell">
                    Beat'em all, let's beat'em all! Max power
                    </td>
                    <td class="description-cell">
                    Beat'em all, let's beat'em all! Max power
                    </td>
                    <td>3</td>
                    <td>
                    <img
                        alt="Placeholder image for question 1"
                        class="img-responsive"
                        style="max-height: 40px"
                        src="https://i.pinimg.com/736x/71/40/0e/71400e3a6d4ed3f54c1c1a26feb2809a.jpg"
                    />
                    </td>
                    <td class="text-center">
                    <input class="form-check-input" type="checkbox" />
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td
                    class="description-cell"
                    data-bs-toggle="modal"
                    data-bs-target="#questionModal"
                    >
                    what you need
                    </td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td>3</td>
                    <td>
                    <img
                        alt="Placeholder image for question 1"
                        class="img-responsive"
                        style="max-height: 40px"
                        src="https://i.pinimg.com/736x/71/40/0e/71400e3a6d4ed3f54c1c1a26feb2809a.jpg"
                    />
                    </td>
                    <td class="text-center">
                    <input class="form-check-input" type="checkbox" />
                    </td>
                </tr>

                <tr>
                    <td>3</td>
                    <td
                    class="description-cell"
                    data-bs-toggle="modal"
                    data-bs-target="#questionModal"
                    >
                    what you need
                    </td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td>3</td>
                    <td>
                    <img
                        alt="Placeholder image for question 1"
                        class="img-responsive"
                        style="max-height: 40px"
                        src="https://i.pinimg.com/736x/71/40/0e/71400e3a6d4ed3f54c1c1a26feb2809a.jpg"
                    />
                    </td>
                    <td class="text-center">
                    <input class="form-check-input" type="checkbox" />
                    </td>
                </tr>

                <tr>
                    <td>4</td>
                    <td
                    class="description-cell"
                    data-bs-toggle="modal"
                    data-bs-target="#questionModal"
                    >
                    what you need
                    </td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td>5</td>
                    <td>
                    <img
                        alt="Placeholder image for question 1"
                        class="img-responsive"
                        style="max-height: 40px"
                        src="https://i.pinimg.com/736x/71/40/0e/71400e3a6d4ed3f54c1c1a26feb2809a.jpg"
                    />
                    </td>
                    <td class="text-center">
                    <input class="form-check-input" type="checkbox" />
                    </td>
                </tr>

                <tr>
                    <td>6</td>
                    <td
                    class="description-cell"
                    data-bs-toggle="modal"
                    data-bs-target="#questionModal"
                    >
                    what you need
                    </td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td>3</td>
                    <td>
                    <img
                        alt="Placeholder image for question 1"
                        class="img-responsive"
                        style="max-height: 40px"
                        src="https://i.pinimg.com/736x/71/40/0e/71400e3a6d4ed3f54c1c1a26feb2809a.jpg"
                    />
                    </td>
                    <td class="text-center">
                    <input class="form-check-input" type="checkbox" />
                    </td>
                </tr>

                <tr>
                    <td>7</td>
                    <td
                    class="description-cell"
                    data-bs-toggle="modal"
                    data-bs-target="#questionModal"
                    >
                    what you need
                    </td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td>3</td>
                    <td>
                    <img
                        alt="Placeholder image for question 1"
                        class="img-responsive"
                        style="max-height: 40px"
                        src="https://i.pinimg.com/736x/71/40/0e/71400e3a6d4ed3f54c1c1a26feb2809a.jpg"
                    />
                    </td>
                    <td class="text-center">
                    <input class="form-check-input" type="checkbox" />
                    </td>
                </tr>

                <tr>
                    <td>8</td>
                    <td
                    class="description-cell"
                    data-bs-toggle="modal"
                    data-bs-target="#questionModal"
                    >
                    what you need
                    </td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td>3</td>
                    <td>
                    <img
                        alt="Placeholder image for question 1"
                        class="img-responsive"
                        style="max-height: 40px"
                        src="https://i.pinimg.com/736x/71/40/0e/71400e3a6d4ed3f54c1c1a26feb2809a.jpg"
                    />
                    </td>
                    <td class="text-center">
                    <input class="form-check-input" type="checkbox" />
                    </td>
                </tr>

                <tr>
                    <td>9</td>
                    <td
                    class="description-cell"
                    data-bs-toggle="modal"
                    data-bs-target="#questionModal"
                    >
                    what you need
                    </td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td class="description-cell">Beat'</td>
                    <td>10</td>
                    <td>
                    <img
                        alt="Placeholder image for question 1"
                        class="img-responsive"
                        style="max-height: 40px"
                        src="https://i.pinimg.com/736x/71/40/0e/71400e3a6d4ed3f54c1c1a26feb2809a.jpg"
                    />
                    </td>
                    <td class="text-center">
                    <input class="form-check-input" type="checkbox" />
                    </td>
                </tr>

                <!-- -------------------------------------Demo----------------------------------------- -->
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
    <section>
        <h2 class="h4 mb-3">Create New Quiz</h2>
        <div class="card shadow-sm">
        <div class="card-body">
            <form>
            <div class="mb-3">
                <label class="form-label" for="quiz-title">Quiz Title</label>
                <input class="form-control" id="quiz-title" type="text" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="quiz-time"
                >Total Time (minutes)</label
                >
                <input class="form-control" id="quiz-time" type="number" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="quiz-questions"
                >Selected Questions</label
                >
                <ul class="list-group" id="selected-questions"></ul>
            </div>
            <button class="btn btn-primary" type="submit">
                Create Quiz
            </button>
            </form>
        </div>
        </div>
    </section>
    </main>

    <!-- Modal for Question Details -->
    <div
    class="modal fade"
    id="questionModal"
    tabindex="-1"
    aria-labelledby="questionModalLabel"
    aria-hidden="true"
    >
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="questionModalLabel">
            Question Details
            </h5>
            <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            ></button>
        </div>
        <div class="modal-body">
            <p><strong>ID:</strong> <span id="modal-id"></span></p>
            <p>
            <strong>Description:</strong>
            <span id="modal-description"></span>
            </p>
            <p><strong>Answer 1:</strong> <span id="modal-answer1"></span></p>
            <p><strong>Answer 2:</strong> <span id="modal-answer2"></span></p>
            <p><strong>Answer 3:</strong> <span id="modal-answer3"></span></p>
            <p><strong>Answer 4:</strong> <span id="modal-answer4"></span></p>
            <p>
            <strong>Correct Answer:</strong>
            <span id="modal-correct"></span>
            </p>
            <div id="modal-image"></div>
        </div>
        <div class="modal-footer">
            <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
            >
            Close
            </button>
        </div>
        </div>
    </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
    const selectedQuestionsList =
        document.getElementById("selected-questions");
    const searchInput = document.getElementById("search-input");
    const sortIdButton = document.getElementById("sort-id");
    const sortDescriptionButton =
        document.getElementById("sort-description");
    const questionsTable = document
        .getElementById("questions-table")
        .getElementsByTagName("tbody")[0];
    const paginationControls = document.getElementById(
        "pagination-controls"
    );
    let allRows = Array.from(questionsTable.getElementsByTagName("tr"));
    const rowsPerPage = 5;
    let currentPage = 1;
    let filteredRows = allRows;

    // Checkbox handling
    function attachCheckboxListeners() {
        const checkboxes = questionsTable.querySelectorAll(
        'input[type="checkbox"]'
        );
        checkboxes.forEach((checkbox) => {
        // Remove existing listeners to prevent duplicates
        const newCheckbox = checkbox.cloneNode(true);
        checkbox.replaceWith(newCheckbox);
        newCheckbox.addEventListener("change", (event) => {
            const row = event.target.closest("tr");
            const questionId = row.cells[0].innerText;
            const questionDescription = row.cells[1].innerText;

            if (event.target.checked) {
            // Check if the question is already selected to prevent duplicates
            const existingItem = selectedQuestionsList.querySelector(
                `li[data-question-id="${questionId}"]`
            );
            if (!existingItem) {
                const listItem = document.createElement("li");
                listItem.className = "list-group-item";
                listItem.textContent = `ID: ${questionId} - ${questionDescription.substring(
                0,
                50
                )}${questionDescription.length > 50 ? "..." : ""}`;
                listItem.dataset.questionId = questionId;
                selectedQuestionsList.appendChild(listItem);
            }
            } else {
            const listItem = selectedQuestionsList.querySelector(
                `li[data-question-id="${questionId}"]`
            );
            if (listItem) {
                selectedQuestionsList.removeChild(listItem);
            }
            }
        });
        });
    }

    // Search functionality
    searchInput.addEventListener("input", () => {
        const filter = searchInput.value.toLowerCase();
        filteredRows = allRows.filter((row) => {
        const description = row.cells[1].innerText.toLowerCase();
        return description.includes(filter);
        });
        currentPage = 1;
        renderTable();
        renderPagination();
    });

    // Sort functionality
    sortIdButton.addEventListener("click", () => sortTable(0));
    sortDescriptionButton.addEventListener("click", () => sortTable(1));

    function sortTable(columnIndex) {
        filteredRows.sort((a, b) => {
        const aText = a.cells[columnIndex].innerText;
        const bText = b.cells[columnIndex].innerText;
        return aText.localeCompare(bText, undefined, { numeric: true });
        });
        currentPage = 1;
        renderTable();
        renderPagination();
    }

    // Modal population
    function attachModalListeners() {
        const descriptionCells =
        document.querySelectorAll(".description-cell");
        descriptionCells.forEach((cell) => {
        // Remove existing listeners to prevent duplicates
        const newCell = cell.cloneNode(true);
        cell.replaceWith(newCell);
        newCell.addEventListener("click", () => {
            const row = newCell.closest("tr");
            document.getElementById("modal-id").textContent =
            row.cells[0].innerText;
            document.getElementById("modal-description").textContent =
            row.cells[1].innerText;
            document.getElementById("modal-answer1").textContent =
            row.cells[2].innerText;
            document.getElementById("modal-answer2").textContent =
            row.cells[3].innerText;
            document.getElementById("modal-answer3").textContent =
            row.cells[4].innerText;
            document.getElementById("modal-answer4").textContent =
            row.cells[5].innerText;
            document.getElementById("modal-correct").textContent =
            row.cells[6].innerText;

            const imgSrc = row.cells[7].querySelector("img")?.src || "";
            const modalImage = document.getElementById("modal-image");
            modalImage.innerHTML = imgSrc
            ? `<img src="${imgSrc}" alt="Question image" class="img-fluid" style="max-height: 200px;">`
            : "No image available";
        });
        });
    }

    // Pagination functionality
    function renderTable() {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        questionsTable.innerHTML = "";
        filteredRows
        .slice(start, end)
        .forEach((row) => questionsTable.appendChild(row));
        attachModalListeners();
        attachCheckboxListeners();
    }

    function renderPagination() {
        const pageCount = Math.ceil(filteredRows.length / rowsPerPage);
        paginationControls.innerHTML = "";

        // Previous button
        const prevItem = document.createElement("li");
        prevItem.className = `page-item ${
        currentPage === 1 ? "disabled" : ""
        }`;
        prevItem.innerHTML = `<a class="page-link" href="#">Previous</a>`;
        prevItem.addEventListener("click", (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            renderTable();
            renderPagination();
        }
        });
        paginationControls.appendChild(prevItem);

        // Page numbers
        for (let i = 1; i <= pageCount; i++) {
        const pageItem = document.createElement("li");
        pageItem.className = `page-item ${
            i === currentPage ? "active" : ""
        }`;
        pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        pageItem.addEventListener("click", (e) => {
            e.preventDefault();
            currentPage = i;
            renderTable();
            renderPagination();
        });
        paginationControls.appendChild(pageItem);
        }

        // Next button
        const nextItem = document.createElement("li");
        nextItem.className = `page-item ${
        currentPage === pageCount ? "disabled" : ""
        }`;
        nextItem.innerHTML = `<a class="page-link" href="#">Next</a>`;
        nextItem.addEventListener("click", (e) => {
        e.preventDefault();
        if (currentPage < pageCount) {
            currentPage++;
            renderTable();
            renderPagination();
        }
        });
        paginationControls.appendChild(nextItem);
    }

    // Initial render
    renderTable();
    renderPagination();
    });
</script>
