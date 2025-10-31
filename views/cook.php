<?php
$page_title = 'Cook & History - Recipe Creator';
$current_page = 'cook';
$recipes = $recipes ?? [];
?>

<h1>Cook & History</h1>
<p class="lead">Start a cooking session and track what you've made.</p>

<section aria-labelledby="session-heading">
  <h2 id="session-heading">Start Cooking Session</h2>
  <?php if (empty($recipes)): ?>
    <div class="card">
      <p>No recipes available. <a href="index.php?action=upload">Upload a recipe</a> to get started!</p>
    </div>
  <?php else: ?>
    <div class="card">
      <h3><?= h($recipes[0]['title']) ?></h3>
      <p class="recipe-meta">
        Created <?= date('M j, Y', strtotime($recipes[0]['created_at'])) ?> • 
        <?= intval($recipes[0]['ingredient_count']) ?> ingredients
      </p>
      
      <form method="get" action="#">
        <fieldset class="form-box">
          <legend class="form-legend">Preparation checklist</legend>
          
          <div class="checkbox-item">
            <input type="checkbox" id="step1" name="step1">
            <label for="step1">Read through entire recipe</label>
          </div>
          
          <div class="checkbox-item">
            <input type="checkbox" id="step2" name="step2">
            <label for="step2">Gather all ingredients</label>
          </div>
          
          <div class="checkbox-item">
            <input type="checkbox" id="step3" name="step3">
            <label for="step3">Prepare cooking equipment</label>
          </div>
          
          <div class="checkbox-item">
            <input type="checkbox" id="step4" name="step4">
            <label for="step4">Wash and chop vegetables</label>
          </div>
        </fieldset>
        
        <button type="submit" class="btn btn--primary">Start Cooking</button>
      </form>
    </div>
  <?php endif; ?>
</section>

<section aria-labelledby="history-heading">
  <h2 id="history-heading">Cooking History</h2>
  <div class="card">
    <ul class="history-list">
      <li class="history-item">
        <div class="history-header">
          <h4>Spaghetti Carbonara</h4>
          <span class="rating">Rated 5/5</span>
        </div>
        <p class="history-meta">October 8, 2025 at 6:30 PM • 4 servings</p>
      </li>
      
      <li class="history-item">
        <div class="history-header">
          <h4>Chicken Tikka Masala</h4>
          <span class="rating">Rated 4/5</span>
        </div>
        <p class="history-meta">October 5, 2025 at 7:00 PM • 6 servings</p>
      </li>
      
      <li class="history-item">
        <div class="history-header">
          <h4>Greek Salad</h4>
          <span class="rating">Rated 5/5</span>
        </div>
        <p class="history-meta">October 3, 2025 at 12:30 PM • 4 servings</p>
      </li>
      
      <li class="history-item">
        <div class="history-header">
          <h4>Pad Thai</h4>
          <span class="rating">Rated 4/5</span>
        </div>
        <p class="history-meta">September 30, 2025 at 6:45 PM • 4 servings</p>
      </li>
    </ul>
    <p style="color: var(--muted); font-size: 0.9rem; margin-top: 1rem;">
      <em>Note: Cooking history tracking will be fully implemented in future sprints.</em>
    </p>
  </div>
</section>

