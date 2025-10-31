<?php
$page_title = 'Recipe Creator - Landing';
$current_page = 'home';
?>

<section aria-labelledby="hero-heading">
  <h1 id="hero-heading">Welcome to Recipe Creator</h1>
  <p class="lead">
    Keep track of what's in your kitchen and find recipes that work with your ingredients.
    No more wasted food or last-minute grocery runs.
  </p>
</section>

<section aria-labelledby="features-heading">
  <h2 id="features-heading">Features</h2>
  <div class="grid">
    <div class="card">
      <h3>Browse Recipes</h3>
      <p>Search our collection by cuisine type, cooking time, or skill level.</p>
      <a href="index.php?action=recipes" class="btn btn--primary">Browse</a>
    </div>
    <div class="card">
      <h3>Recipe Chat</h3>
      <p>Type what you want to cook and get personalized suggestions.</p>
      <a href="index.php?action=chat" class="btn btn--primary">Start Chat</a>
    </div>
    <div class="card">
      <h3>Fridge Matcher</h3>
      <p>Find recipes based on what's currently in your kitchen.</p>
      <a href="index.php?action=match" class="btn btn--primary">Match Now</a>
    </div>
  </div>
</section>

<section aria-labelledby="get-started">
  <h2 id="get-started">Get Started</h2>
  <div class="grid">
    <div class="card">
      <h3>1. Add Ingredients</h3>
      <p>Tell us what you have in your kitchen.</p>
      <a href="index.php?action=pantry" class="btn">Add Items</a>
    </div>
    <div class="card">
      <h3>2. Upload Recipes</h3>
      <p>Save recipes from websites or add your own.</p>
      <a href="index.php?action=upload" class="btn">Add Recipe</a>
    </div>
    <div class="card">
      <h3>3. Start Cooking</h3>
      <p>Follow recipes and track what you've made.</p>
      <a href="index.php?action=cook" class="btn">Cook</a>
    </div>
  </div>
</section>

