<div class="container mt-5">
  <h1 class="text-center mb-4">Multiple Choice Test Form</h1>
  <form id="testForm">
    <div id="questions">
      <div class="question-container mb-4 border p-3 rounded shadow">
        <div class="container">
          <div class="row">
            <div class="col-12 col-md-6">
              <h2>Question:</h2>
            </div>
            <div class="col-12 col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                <select class="form-select" name="category" required>
                  <option value="" disabled selected>Select a category</option>
                  <option value="Math">Math</option>
                  <option value="Science">Science</option>
                  <option value="History">History</option>
                  <option value="Literature">Literature</option>
                  <option value="Geography">Geography</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="container">
          <div class="row">
            <div class="mb-3">
              <label class="form-label fw-bold">Question Text <span class="text-danger">*</span></label>
              <textarea
                class="form-control"
                name="description"
                placeholder="Enter your question"
                required
                rows="3"
              ></textarea>
            </div>

            <div class="mb-3">
              <label for="image-input" class="form-label fw-bold">
                Choose Image 
              </label>
              <div class="custom-file-input-wrapper">
                <label for="image-input" class="btn btn-outline-primary">Upload Image</label>
                <input
                  type="file"
                  id="image-input"
                  name="image"
                  accept="image/*"
                  onchange="previewImage(event)"
                />
                <small id="image-filename" class="text-muted d-block mt-1"></small>
              </div>
            </div>
            <input type="hidden" name="existingImagePath" id="existingImagePath" />

            <div class="mb-3 d-flex justify-content-center">
              <img
                id="image-preview"
                src="#"
                alt="Image Preview"
                class="img-thumbnail d-none"
                style="max-height: 200px;"
              />
            </div>

            <!-- Answer Options -->
            <div class="mb-3">
              <label class="form-label fw-bold">Answer Options <span class="text-danger">*</span></label>

              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="correctAnswer" value="1" required />
                <label class="form-check-label me-2">Option A<span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control mt-1"
                  name="ans1"
                  placeholder="Option A"
                  required
                />
              </div>

              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="correctAnswer" value="2" />
                <label class="form-check-label me-2">Option B<span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control mt-1"
                  name="ans2"
                  placeholder="Option B"
                  required
                />
              </div>

              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="correctAnswer" value="3" />
                <label class="form-check-label me-2">Option C<span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control mt-1"
                  name="ans3"
                  placeholder="Option C"
                  required
                />
              </div>

              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="correctAnswer" value="4" />
                <label class="form-check-label me-2">Option D<span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control mt-1"
                  name="ans4"
                  placeholder="Option D"
                  required
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <input type="submit" class="btn btn-success" value="Submit" />
  </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', async function () {
  const requestData = {
    controller: 'question',
    action: 'getCacheQuestion',
    ajax: 1
  };

  try {
    const response = await fetch('index.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestData)
    });

    const result = await response.json();

    if (result.success) {
      const data = result.data;
      const descriptionField = document.querySelector('textarea[name="description"]');
      if (descriptionField) {
        descriptionField.value = data.description || ''; 
      }

      // Check if category exists, if not, leave it empty
      const categoryField = document.querySelector('select[name="category"]');
      if (categoryField) {
        categoryField.value = data.category || ''; // Leave empty if null or undefined
      }

      // Check and pre-fill answer options, if null leave it empty
      const ans1Field = document.querySelector('input[name="ans1"]');
      if (ans1Field) {
        ans1Field.value = data.ans1 || ''; // Leave empty if null or undefined
      }

      const ans2Field = document.querySelector('input[name="ans2"]');
      if (ans2Field) {
        ans2Field.value = data.ans2 || ''; // Leave empty if null or undefined
      }

      const ans3Field = document.querySelector('input[name="ans3"]');
      if (ans3Field) {
        ans3Field.value = data.ans3 || ''; // Leave empty if null or undefined
      }

      const ans4Field = document.querySelector('input[name="ans4"]');
      if (ans4Field) {
        ans4Field.value = data.ans4 || ''; // Leave empty if null or undefined
      }

      // Check and set the correct answer (radio button), if null skip
      const correctAnswerInput = document.querySelector(`input[name="correctAnswer"][value="${data.correct_answer}"]`);
      if (correctAnswerInput) {
        correctAnswerInput.checked = true;
      }

      // Check and display image preview if the image path is provided
      const imagePreview = document.getElementById("image-preview");
      // if (imagePreview) {
      //   if (data.image_path) {
      //     imagePreview.src = "public/" + data.image_path;
      //     imagePreview.classList.remove("d-none");
      //   } else {
      //     imagePreview.classList.add("d-none"); // Hide the image preview if no path is available
      //   }
      // }
      if (data.image_path) {
        imagePreview.src = "public/" + data.image_path;
        imagePreview.classList.remove("d-none");
        document.getElementById("existingImagePath").value = data.image_path; // set hidden field
      } else {
        imagePreview.classList.add("d-none");
        document.getElementById("existingImagePath").value = "";
      }

    } else {
      console.error("No cached data found or an error occurred.");
    }
  } catch (error) {
    console.error("Error occurred while loading cached data:", error);
  }
});



  let debounceTimer;
  function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById("image-preview");

    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.classList.remove("d-none");
      };
      reader.readAsDataURL(input.files[0]);
    } else {
      preview.src = "#";
      preview.classList.add("d-none");
    }
  }

  function debounce(func, delay) {
    return function (...args) {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => func.apply(this, args), delay);
    };
  }

  document.getElementById("testForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    formData.append("ajax", 1);
    formData.append("controller", "question");
    formData.append("action", "submitQuestion");

    const requiredFields = ["category", "description", "ans1", "ans2", "ans3", "ans4", "correctAnswer"];
    for (const field of requiredFields) {
      if (!formData.get(field)) {
        alert(`Please fill out the required field: ${field}`);
        return;
      }
    }
    console.log("existingImagePath:", formData.get("existingImagePath"));
    try {
      const response = await fetch("index.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (response.ok && result.success) {
        alert("Question submitted successfully!");
        form.reset();
        document.getElementById("image-preview").classList.add("d-none");
      } else {
        alert(result.message || "An error occurred while submitting.");
      }
    } catch (error) {
      console.error("Submission error:", error);
      alert("Failed to submit. Please try again.");
    }
  });

  async function cacheFormData(formData) {
    try {
      const response = await fetch("index.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (response.ok && result.success) {
        console.log("Form data cached successfully!");
      } else {
        console.error(result.message || "An error occurred while caching.");
      }
    } catch (error) {
      console.error("Error while caching data:", error);
    }
  }
  
  document.getElementById("testForm").addEventListener("input", debounce(async function (e) {
    const form = e.target.closest("form");
    const formData = new FormData(form);
    formData.append("ajax", 1);
    formData.append("controller", "question");
    formData.append("action", "cacheQuestion");
    await cacheFormData(formData);
  }, 1000));
</script>

<style>
  .custom-file-input-wrapper input[type="file"] {
  display: none;
}

</style>