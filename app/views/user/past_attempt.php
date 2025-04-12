<?php
$test_attempt_id = isset($_GET['test_attempt_id']) ? $_GET['test_attempt_id'] : 1;
$current_page = isset($_GET['num_page']) ? $_GET['num_page'] : 1;

$test_attempts = [
    1 => [
        'name' => 'English Grammar Test',
        'questions' => [
            ['question' => 'What is the correct form of the verb?', 'choices' => ['go', 'went', 'goes', 'going'], 'correct' => 'goes', 'selected' => 'goes'],
            ['question' => 'Choose the correct synonym for "happy"', 'choices' => ['angry', 'joyful', 'sad', 'bored'], 'correct' => 'joyful', 'selected' => 'angry'],
            ['question' => 'Choose the correct option for the sentence structure', 'choices' => ['complex', 'simple', 'compound', 'compound-complex'], 'correct' => 'simple', 'selected' => 'complex'],
        ],
    ],
    2 => [
        'name' => 'Science Quiz',
        'questions' => [
            ['question' => 'What is the chemical symbol for water?', 'choices' => ['H2O', 'O2', 'CO2', 'N2'], 'correct' => 'H2O', 'selected' => 'CO2'],
            ['question' => 'What is the speed of light?', 'choices' => ['3x10^8 m/s', '2x10^8 m/s', '4x10^8 m/s', '1x10^8 m/s'], 'correct' => '3x10^8 m/s', 'selected' => '3x10^8 m/s'],
            ['question' => 'What is the atomic number of oxygen?', 'choices' => ['6', '8', '12', '10'], 'correct' => '8', 'selected' => '8'],
        ],
    ],
];

$test = $test_attempts[$test_attempt_id];
?>

<div class="container my-5">
  <h3 class="mb-4">Test Review: <?php echo $test['name']; ?></h3>

  <?php foreach ($test['questions'] as $index => $question): ?>
    <div class="mb-4">
      <h5><?php echo ($index + 1) . '. ' . $question['question']; ?></h5>
      <ul class="list-group">
        <?php foreach ($question['choices'] as $choice): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>
              <i class="bi bi-circle<?php echo ($question['selected'] == $choice) ? '-fill' : ''; ?> me-2"></i> <?php echo $choice; ?>
            </span>
            
            <?php if ($question['selected'] == $choice): ?>
              <?php if ($choice == $question['correct']): ?>
                <i class="bi bi-check-circle-fill text-success"></i> 
              <?php else: ?>
                <i class="bi bi-x-circle-fill text-danger"></i> 
              <?php endif; ?>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endforeach; ?>

  <div style="max-height: 500px; overflow-y: auto;">
  </div>

  <a href="index.php?page=history&num_page=<?php echo $current_page; ?>" class="btn btn-primary mt-3">Back to history</a>
</div>
