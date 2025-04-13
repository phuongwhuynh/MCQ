<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Multiple Choice Test Form</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <style>
      .remove-button {
        color: red;
        cursor: pointer;
      }
      body {
        font-size: 1.4rem;
      }
      input[type="radio"] {
        transform: scale(1.5);
        margin-right: 10px;
      }
    </style>
  </head>

  <body>
    <div class="container mt-5">
      <h1 class="text-center mb-4">Multiple Choice Test Form</h1>
      <form id="testForm">
        <div id="questions">
          <div class="question-container mb-4 border p-3 rounded">
            <div class="container">
              <div class="row">
                <div class="col-12 col-md-6">
                  <h2>Question 1:</h2>
                </div>
                <div class="col-12 col-md-6">
                  <div class="mb-3">
                    <select class="form-select" name="category[]" required>
                      <option value="" disabled selected>
                        Select a category
                      </option>
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
                  <label class="form-label" style="font-size:medium">Question Image (optional)</label>
                  <input type="file" class="form-control mb-1" name="questionImage[]" accept="image/*">
                  <input
                    type="text"
                    class="form-control"
                    name="question[]"
                    placeholder="Enter your question"
                    required
                  />
                </div>
                <div class="p-1">
                  <input type="radio" name="correctAnswer[]" value="B" />
                  Option A
                </div>
                <div class="p-1">
                  <input
                    type="text"
                    class="form-control"
                    name="optionA[]"
                    placeholder="Option A"
                    required
                  />
                </div>

                <div class="p-1">
                  <input type="radio" name="correctAnswer[]" value="B" />
                  Option B
                </div>

                <div class="p-1">
                  <input
                    type="text"
                    class="form-control"
                    name="optionB[]"
                    placeholder="Option B"
                    required
                  />
                </div>
                <div class="p-1">
                  <input type="radio" name="correctAnswer[]" value="C" />
                  Option C
                </div>
                <div class="p-1">
                  <input
                    type="text"
                    class="form-control"
                    name="optionC[]"
                    placeholder="Option C"
                    required
                  />
                </div>
                <div class="p-1">
                  <input type="radio" name="correctAnswer[]" value="D" />
                  Option D
                </div>
                <div class="p-1">
                  <input
                    type="text"
                    class="form-control"
                    name="optionD[]"
                    placeholder="Option D"
                    required
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <br /><br />
        <input type="submit" class="btn btn-success" value="Submit" />
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
<?php
// Assuming $conn is your database connection established elsewhere

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields are set and are arrays
    if (isset($_POST['category'], $_POST['question'], $_POST['optionA'], $_POST['optionB'], $_POST['optionC'], $_POST['optionD'], $_POST['correctAnswer']) &&
        is_array($_POST['category']) && is_array($_POST['question']) && is_array($_POST['optionA']) &&
        is_array($_POST['optionB']) && is_array($_POST['optionC']) && is_array($_POST['optionD']) &&
        is_array($_POST['correctAnswer']))
    {
        $categories = $_POST['category'];
        $questions = $_POST['question'];
        $optionA = $_POST['optionA']; 
        $optionB = $_POST['optionB']; 
        $optionC = $_POST['optionC']; 
        $optionD = $_POST['optionD']; 
        $correctAnswers = $_POST['correctAnswer']; // Corrected key


        $stmt = $conn->prepare("INSERT INTO question (category, question, optionA, optionB, optionC, optionD, correctAnswer) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssss", $category, $question, $optA, $optB, $optC, $optD, $correctAnswer);

        

            for ($i = 0; $i < $numQuestions; $i++) {

                $category = $categories[$i];
                $question = $questions[$i];
                $optA = $optionA[$i];
                $optB = $optionB[$i];
                $optC = $optionC[$i];
                $optD = $optionD[$i];
                $correctAnswer = $correctAnswers[$i]; // This should correspond to the value ('A', 'B', 'C', or 'D') of the selected radio button for this question


                if (!$stmt->execute()) {

                    error_log("Execute failed for question index $i: (" . $stmt->errno . ") " . $stmt->error);
                    //echo "Error inserting question: " . htmlspecialchars($question); break;
                }
            }

            $stmt->close();


            // echo "Data submitted successfully!";




    } else {
        echo "Error: Required form data is missing or not in the expected format.";
        error_log("Missing or invalid POST data: " . print_r($_POST, true));
    }

} else {
    // echo "Invalid request method.";
    
}
?>
