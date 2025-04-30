<?php
// Define the JSON file path (same as main page)
$filename = 'awesome_osint.json';

// Check if file exists and is readable (same as main page)
if (!file_exists($filename)) {
    echo "<script>console.error('Error: JSON file not found: $filename');</script>";
} else if (!is_readable($filename)) {
    echo "<script>console.error('Error: Cannot read JSON file: $filename (check permissions)');</script>";
} else {
    // Try to parse the JSON
    $jsonContent = file_get_contents($filename);
    $rawData = json_decode($jsonContent, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - OSINT Tool Explorer</title>
  <meta name="description" content="Contact the OSINT Tool Explorer team with questions, suggestions, or feedback about our directory of Open Source Intelligence tools.">
  
  <!-- Use the same styles as the main page -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --text-color: #333;
      --bg-color: #f0f0f0;
      --card-bg: #fff;
      --border-color: #ccc;
      --primary-color: #007bff;
      --primary-hover: #0056b3;
      --focus-color: #0070f3;
      --error-bg: #ffebee;
      --error-color: #c62828;
      --error-border: #ef9a9a;
      --success-bg: #e8f5e9;
      --success-color: #2e7d32;
      --success-border: #a5d6a7;
      --badge-text: #fff;
      --badge-bg: #666;
      --hover-bg: rgba(0,0,0,0.05);
      --card-shadow: 0 2px 4px rgba(0,0,0,0.1);
      --card-hover-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    body.dark {
      --text-color: #eee;
      --bg-color: #1c1c1c;
      --card-bg: #2b2b2b;
      --border-color: #444;
      --primary-color: #4d94ff;
      --primary-hover: #3a7cd5;
      --focus-color: #5e9eff;
      --error-bg: #4a1c1c;
      --error-color: #ef9a9a;
      --error-border: #c62828;
      --success-bg: #1c3e1e;
      --success-color: #a5d6a7;
      --success-border: #2e7d32;
      --badge-text: #fff;
      --badge-bg: #555;
      --hover-bg: rgba(255,255,255,0.1);
      --card-shadow: 0 2px 4px rgba(0,0,0,0.3);
      --card-hover-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    body {
      font-family: Arial, sans-serif;
      margin: 2rem;
      background-color: var(--bg-color);
      color: var(--text-color);
      transition: background-color 0.3s, color 0.3s;
      line-height: 1.6;
    }
    
    .container {
      max-width: 800px;
      margin: 0 auto;
    }
    
    h1 {
      margin-bottom: 1.5rem;
    }
    
    .contact-form {
      background: var(--card-bg);
      padding: 2rem;
      border-radius: 8px;
      box-shadow: var(--card-shadow);
      border: 1px solid var(--border-color);
    }
    
    .form-group {
      margin-bottom: 1.5rem;
    }
    
    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: bold;
    }
    
    input, textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid var(--border-color);
      border-radius: 4px;
      background: var(--card-bg);
      color: var(--text-color);
    }
    
    textarea {
      min-height: 150px;
      resize: vertical;
    }
    
    button[type="submit"] {
      background: var(--primary-color);
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.2s;
    }
    
    button[type="submit"]:hover {
      background: var(--primary-hover);
    }
    
    .status-message {
      margin-top: 1rem;
      padding: 1rem;
      border-radius: 4px;
    }
    
    .status-message.success {
      background: var(--success-bg);
      color: var(--success-color);
      border: 1px solid var(--success-border);
    }
    
    .status-message.error {
      background: var(--error-bg);
      color: var(--error-color);
      border: 1px solid var(--error-border);
    }
    
    .toggle-theme { 
      cursor: pointer; 
      padding: 0.5rem 1rem;
      border-radius: 4px;
      border: 1px solid var(--border-color);
      background: var(--card-bg);
      color: var(--text-color);
      display: inline-flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 1.5rem;
    }
    
    /* Back link */
    .back-link {
      display: inline-block;
      margin-bottom: 1.5rem;
      color: var(--primary-color);
      text-decoration: none;
    }
    
    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to OSINT Tool Explorer</a>
    
    <button class="toggle-theme" aria-label="Toggle dark mode">
      <i class="fas fa-moon"></i> <span class="theme-text">Toggle Dark Mode</span>
    </button>
    
    <h1>Contact Us</h1>
    
    <div class="contact-form">
      <form id="contact-form" method="POST" action="process_contact.php">
        <div class="form-group">
          <label for="name">Your Name</label>
          <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
          <label for="email">Your Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" required>
        </div>
        
        <div class="form-group">
          <label for="message">Your Message</label>
          <textarea id="message" name="message" required></textarea>
        </div>
        
        <button type="submit">Send Message</button>
      </form>
      
      <div id="status-message" class="status-message" role="status" aria-live="polite" style="display: none;"></div>
    </div>
  </div>
  
  <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
  <div class="h-captcha" data-sitekey="90ca1c4a-c256-48fc-adc0-ec32e1a00120"></div>
  
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script>
    // Handle form submission
    $('#contact-form').on('submit', function(e) {
      e.preventDefault();
      
      const form = $(this);
      const statusMessage = $('#status-message');
      
      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        success: function(response) {
          statusMessage.removeClass('error').addClass('success').text('Thank you! Your message has been sent.').show();
          form.trigger('reset');
        },
        error: function(xhr) {
          statusMessage.removeClass('success').addClass('error').text('Error: ' + xhr.responseText).show();
        }
      });
    });
    
    // Dark mode toggle (same as main page)
    $('.toggle-theme').on('click', function () {
      document.body.classList.toggle('dark');
      const isDark = document.body.classList.contains('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      
      const icon = $(this).find('i');
      const themeText = $(this).find('.theme-text');
      
      if (isDark) {
        icon.removeClass('fa-moon').addClass('fa-sun');
        themeText.text('Toggle Light Mode');
        $(this).attr('aria-label', 'Toggle light mode');
      } else {
        icon.removeClass('fa-sun').addClass('fa-moon');
        themeText.text('Toggle Dark Mode');
        $(this).attr('aria-label', 'Toggle dark mode');
      }
    });
    
    // Check for saved theme preference
    if (localStorage.getItem('theme') === 'dark') {
      document.body.classList.add('dark');
      $('.toggle-theme i').removeClass('fa-moon').addClass('fa-sun');
      $('.toggle-theme .theme-text').text('Toggle Light Mode');
      $('.toggle-theme').attr('aria-label', 'Toggle light mode');
    }
  </script>
</body>
</html>