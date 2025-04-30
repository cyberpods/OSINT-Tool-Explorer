<?php
// Define the JSON file path
$filename = 'awesome_osint.json';

// Check if file exists and is readable
if (!file_exists($filename)) {
    echo "<script>console.error('Error: JSON file not found: $filename');</script>";
    $treeData = [
        "name" => "OSINT", 
        "state" => ["opened" => true], 
        "children" => [
            ["name" => "Error", "children" => [
                ["name" => "JSON file not found", "description" => "Please create awesome_osint.json"]
            ]]
        ]
    ];
} else if (!is_readable($filename)) {
    echo "<script>console.error('Error: Cannot read JSON file: $filename (check permissions)');</script>";
    $treeData = [
        "name" => "OSINT", 
        "state" => ["opened" => true], 
        "children" => [
            ["name" => "Error", "children" => [
                ["name" => "Cannot read JSON file", "description" => "Check file permissions"]
            ]]
        ]
    ];
} else {
    // Try to parse the JSON
    $jsonContent = file_get_contents($filename);
    $rawData = json_decode($jsonContent, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<script>console.error('Error parsing JSON: " . json_last_error_msg() . "');</script>";
        $treeData = [
            "name" => "OSINT", 
            "state" => ["opened" => true], 
            "children" => [
                ["name" => "Error", "children" => [
                    ["name" => "Invalid JSON format", "description" => json_last_error_msg()]
                ]]
            ]
        ];
    } else {
        // Add icons to common categories
        $icons = [
          'Search' => '🔍', 'Social' => '👥', 'Username' => '🆔', 'Email' => '📧',
          'Phone' => '📱', 'Reddit' => '👽', 'Facebook' => '📘', 'Twitter' => '🐦',
          'Dark Web' => '🕸️', 'Pastebin' => '📄', 'People' => '👤', 'Company' => '🏢'
        ];

        // Define category color coding
        $categoryColors = [
            'Search' => '#4285F4',
            'Social' => '#EA4335', 
            'Username' => '#FBBC05',
            'Email' => '#34A853',
            'Phone' => '#FF6D01',
            'Dark Web' => '#8E24AA',
            'People' => '#16A085',
            'Company' => '#2980B9'
        ];

        // Tool type tags
        $toolTypes = [
            'free' => 'Free',
            'paid' => 'Paid',
            'api' => 'API',
            'database' => 'Database',
            'search' => 'Search Engine'
        ];

        // Count totals
        $totalTools = 0;
        $totalCategories = count($rawData);

        // Convert to nested tree format
        function convertToTree($data, $icons, $categoryColors, &$totalTools) {
            $tree = ["name" => "OSINT", "state" => ["opened" => true], "children" => []];

            foreach ($data as $category => $tools) {
                $category = preg_replace('/^\[↑\]\(.*?\)\s*/', '', $category);
                if (empty($tools)) continue;

                $categoryColor = '#666666';  // Default color
                $categoryIcon = '';
                
                foreach ($icons as $keyword => $icon) {
                    if (stripos($category, $keyword) !== false) {
                        $categoryIcon = $icon . ' ';
                        if (isset($categoryColors[$keyword])) {
                            $categoryColor = $categoryColors[$keyword];
                        }
                        break;
                    }
                }

                $categoryNode = [
                    "name" => $categoryIcon . $category,
                    "state" => ["opened" => false], 
                    "children" => [],
                    "color" => $categoryColor
                ];
                
                foreach ($tools as $tool) {
                    if (!isset($tool['name']) || !isset($tool['url'])) continue;
                    $totalTools++;
                    
                    $label = $tool['name'];
                    if (!empty($tool['description'])) {
                        $label .= " – " . $tool['description'];
                    }
                    
                    // Generate random tool type for demo (in real app, this would come from data)
                    $toolTypeKeys = array_keys($GLOBALS['toolTypes']);
                    $randomType = $toolTypeKeys[array_rand($toolTypeKeys)];
                    
                    $categoryNode['children'][] = [
                        "label" => $label,
                        "name" => $tool['name'],
                        "url" => $tool['url'],
                        "description" => $tool['description'] ?? '',
                        "toolType" => $randomType
                    ];
                }
                $tree['children'][] = $categoryNode;
            }

            usort($tree['children'], fn($a, $b) => strcmp($a['name'], $b['name']));
            return $tree;
        }

        $treeData = convertToTree($rawData, $icons, $categoryColors, $totalTools);
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OSINT Tool Explorer - Comprehensive Directory of Open Source Intelligence Tools</title>
  <meta name="description" content="Discover and explore a comprehensive directory of Open Source Intelligence (OSINT) tools organized by category. Find resources for investigations, research, and digital intelligence gathering.">
  <meta name="keywords" content="OSINT, Open Source Intelligence, cybersecurity tools, investigation tools, digital research, security tools, intelligence gathering">
  <meta name="author" content="OSINT Tool Explorer">
  <meta name="robots" content="index, follow">
  
  <!-- Open Graph / Social Media Meta Tags -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="OSINT Tool Explorer - Find the Best Open Source Intelligence Tools">
  <meta property="og:description" content="Discover and explore a comprehensive directory of Open Source Intelligence (OSINT) tools organized by category.">
  <meta property="og:image" content="https://example.com/osint-tool-explorer.jpg">
  <meta property="og:url" content="https://example.com/osint-tool-explorer">
  
  <!-- Twitter Card Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="OSINT Tool Explorer - Find the Best OSINT Tools">
  <meta name="twitter:description" content="Comprehensive directory of Open Source Intelligence (OSINT) tools for research and investigations.">
  <meta name="twitter:image" content="https://example.com/osint-tool-explorer.jpg">
  
  <!-- Canonical URL -->
  <link rel="canonical" href="https://example.com/osint-tool-explorer">
  
  <!-- Favicon -->
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/themes/default/style.min.css">
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
    
    /* Skip to content link for accessibility */
    .skip-to-content {
      position: absolute;
      top: -40px;
      left: 0;
      background: var(--primary-color);
      color: white;
      padding: 8px;
      z-index: 100;
      transition: top 0.3s;
    }
    
    .skip-to-content:focus {
      top: 0;
    }
    
    /* Style for count badges in tree */
    .count-badge {
      display: inline-block;
      min-width: 18px;
      text-align: center;
      font-weight: normal;
      font-size: 11px !important;
      border-radius: 10px;
      padding: 1px 6px;
      margin-left: 8px;
      color: var(--badge-text);
      background-color: var(--badge-bg);
    }
    
    h1 { 
      margin-bottom: 1rem; 
      position: relative;
    }
    
    /* Keyboard shortcuts help */
    .keyboard-help {
      position: absolute;
      top: 0;
      right: 0;
      font-size: 14px;
      cursor: pointer;
    }
    
    .keyboard-help-dialog {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 6px;
      padding: 20px;
      z-index: 1000;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      max-width: 600px;
      width: 90%;
    }
    
    .keyboard-help-dialog.visible {
      display: block;
    }
    
    .keyboard-help-dialog h2 {
      margin-top: 0;
    }
    
    .keyboard-shortcut {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
    }
    
    .key {
      background: var(--hover-bg);
      padding: 2px 8px;
      border-radius: 4px;
      font-family: monospace;
      margin-left: 10px;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .description {
      background-color: var(--hover-bg);
      padding: 15px;
      border-radius: 6px;
      margin-bottom: 20px;
      line-height: 1.5;
      border-left: 4px solid var(--primary-color);
    }
    
    .toolbar {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1rem;
      flex-wrap: wrap;
    }
    
    .tool-stats {
      font-size: 14px;
      background: var(--hover-bg);
      padding: 5px 10px;
      border-radius: 4px;
      margin-left: auto;
    }
    
    .view-toggle {
      display: flex;
      gap: 5px;
      margin-left: 10px;
    }
    
    .view-btn {
      padding: 5px 10px;
      border: 1px solid var(--border-color);
      background: var(--card-bg);
      cursor: pointer;
      border-radius: 4px;
      color: var(--text-color);
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 14px;
    }
    
    .view-btn:focus-visible {
      outline: 3px solid var(--focus-color);
    }
    
    .view-btn.active {
      background: var(--primary-color);
      color: white;
      border-color: var(--primary-hover);
    }
    
    #tree { 
      border: 1px solid var(--border-color); 
      padding: 1rem; 
      background: var(--card-bg); 
      border-radius: 4px;
      box-shadow: var(--card-shadow);
      display: block;
      min-height: 400px;
    }
    
    #search { 
      padding: 0.5rem; 
      width: 300px; 
      border-radius: 4px;
      border: 1px solid var(--border-color);
      background: var(--card-bg);
      color: var(--text-color);
    }
    
    #search:focus-visible {
      outline: 3px solid var(--focus-color);
      border-color: var(--primary-color);
    }
    
    .toggle-theme { 
      cursor: pointer; 
      padding: 0.5rem 1rem;
      border-radius: 4px;
      border: 1px solid var(--border-color);
      background: var(--card-bg);
      color: var(--text-color);
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .toggle-theme:focus-visible {
      outline: 3px solid var(--focus-color);
    }
    
    .jstree-anchor { 
      text-decoration: none; 
    }
    
    .clickable-node > .jstree-anchor {
      cursor: pointer;
      color: var(--primary-color);
    }
    
    .clickable-node > .jstree-anchor:hover {
      text-decoration: underline;
    }
    
    .tool-actions {
      display: none;
      margin-left: 5px;
    }
    
    .jstree-hovered .tool-actions,
    .jstree-anchor:focus .tool-actions {
      display: inline-flex;
    }
    
    .tool-actions button {
      background: none;
      border: none;
      cursor: pointer;
      color: var(--text-color);
      padding: 2px 4px;
      font-size: 12px;
    }
    
    .tool-actions button:hover,
    .tool-actions button:focus {
      color: var(--primary-color);
    }
    
    #status {
      margin-top: 10px;
      padding: 10px;
      border-radius: 4px;
      display: none;
    }
    
    #status.error {
      display: block;
      background-color: var(--error-bg);
      color: var(--error-color);
      border: 1px solid var(--error-border);
    }
    
    #status.success {
      display: block;
      background-color: var(--success-bg);
      color: var(--success-color);
      border: 1px solid var(--success-border);
    }
    
    .category-node {
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    /* Grid view styles */
    #grid-view {
      display: none;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      padding: 1rem;
      background: var(--card-bg);
      border-radius: 4px;
      border: 1px solid var(--border-color);
      box-shadow: var(--card-shadow);
      min-height: 400px;
    }
    
    .tool-card {
      border: 1px solid var(--border-color);
      border-radius: 6px;
      overflow: hidden;
      transition: transform 0.2s, box-shadow 0.2s;
      background: var(--card-bg);
    }
    
    .tool-card:hover,
    .tool-card:focus-within {
      transform: translateY(-5px);
      box-shadow: var(--card-hover-shadow);
    }
    
    .tool-card-header {
      display: flex;
      align-items: center;
      padding: 10px;
      border-bottom: 1px solid var(--border-color);
      background: var(--hover-bg);
    }
    
    .tool-card-icon {
      width: 24px;
      height: 24px;
      margin-right: 10px;
    }
    
    .tool-card-title {
      font-weight: bold;
      flex-grow: 1;
    }
    
    .tool-card-type {
      font-size: 12px;
      padding: 2px 6px;
      background: var(--hover-bg);
      border-radius: 4px;
      color: var(--text-color);
    }
    
    .tool-card-content {
      padding: 15px;
    }
    
    .tool-card-description {
      margin-bottom: 15px;
      color: var(--text-color);
      opacity: 0.8;
    }
    
    .tool-card-actions {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
    }
    
    .tool-card-category {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 4px;
      color: white;
      font-size: 12px;
    }
    
    .tool-card-buttons {
      display: flex;
      gap: 8px;
    }
    
    .tool-card-buttons button {
      background: none;
      border: none;
      color: var(--primary-color);
      cursor: pointer;
      padding: 5px;
      transition: color 0.2s;
    }
    
    .tool-card-buttons button:hover,
    .tool-card-buttons button:focus {
      text-decoration: underline;
      color: var(--primary-hover);
    }
    
    .tool-card-buttons button:focus-visible {
      outline: 3px solid var(--focus-color);
      border-radius: 4px;
    }
    
    /* Focus styles */
    .keyboard-focus,
    *:focus-visible {
      outline: 3px solid var(--focus-color);
      outline-offset: 2px;
    }
    
    /* Back to top button */
    .back-to-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--primary-color);
      color: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0;
      transition: opacity 0.3s;
      border: none;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
      z-index: 99;
    }
    
    .back-to-top.visible {
      opacity: 1;
    }
    
    .back-to-top:focus-visible {
      outline: 3px solid var(--focus-color);
    }
    
    /* Social sharing buttons */
    .social-sharing {
      display: flex;
      gap: 10px;
      margin-top: 20px;
      margin-bottom: 20px;
    }
    
    .social-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      color: white;
      font-size: 18px;
      cursor: pointer;
      border: none;
      transition: transform 0.2s, opacity 0.2s;
    }
    
    .social-btn:hover {
      transform: translateY(-2px);
      opacity: 0.9;
    }
    
    .social-btn:focus-visible {
      outline: 3px solid var(--focus-color);
    }
    
    .twitter-btn {
      background: #1DA1F2;
    }
    
    .facebook-btn {
      background: #4267B2;
    }
    
    .linkedin-btn {
      background: #0077B5;
    }
    
    .reddit-btn {
      background: #FF5700;
    }
    
    /* Social buttons in tool cards */
    .tool-card-social {
      display: flex;
      gap: 5px;
      margin-top: 8px;
    }
    
    .tool-card-social button {
      width: 24px;
      height: 24px;
      font-size: 12px;
    }
    
    /* Accessibility improvements */
    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border-width: 0;
    }
    
    /* Overlay for keyboard help */
    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.5);
      z-index: 999;
    }
    
    .overlay.visible {
      display: block;
    }
    
    /* Footer Styles */
    .site-footer {
      background-color: var(--card-bg);
      color: var(--text-color);
      padding: 2rem 0;
      margin-top: 3rem;
      border-top: 1px solid var(--border-color);
      font-size: 14px;
    }
    
    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-bottom: 2rem;
    }
    
    .footer-section h3 {
      font-size: 1.2rem;
      margin-bottom: 1rem;
      position: relative;
      padding-bottom: 0.5rem;
    }
    
    .footer-section h3::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 2px;
      background-color: var(--primary-color);
    }
    
    .footer-section ul {
      list-style: none;
      padding: 0;
    }
    
    .footer-section ul li {
      margin-bottom: 0.5rem;
    }
    
    .footer-section a {
      color: var(--primary-color);
      text-decoration: none;
      transition: color 0.2s;
    }
    
    .footer-section a:hover,
    .footer-section a:focus {
      color: var(--primary-hover);
      text-decoration: underline;
    }
    
    .social-links {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }
    
    .social-links a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background-color: var(--hover-bg);
      color: var(--text-color);
      transition: background-color 0.2s, color 0.2s;
    }
    
    .social-links a:hover,
    .social-links a:focus {
      background-color: var(--primary-color);
      color: white;
    }
    
    .footer-legal {
      padding: 1.5rem 0;
      border-top: 1px solid var(--border-color);
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin-bottom: 1.5rem;
    }
    
    .footer-legal h4 {
      font-size: 1rem;
      margin-bottom: 0.5rem;
    }
    
    .footer-legal p {
      opacity: 0.8;
      line-height: 1.6;
      font-size: 0.85rem;
    }
    
    .copyright {
      text-align: center;
      padding-top: 1.5rem;
      border-top: 1px solid var(--border-color);
      opacity: 0.7;
    }
    
    /* SEO-related styles */
    h1, h2, h3 {
      color: var(--text-color);
    }
    
    .seo-highlight {
      font-weight: bold;
      color: var(--primary-color);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      body {
        margin: 1rem;
      }
      
      .toolbar {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .tool-stats {
        margin-left: 0;
        margin-top: 10px;
        width: 100%;
      }
      
      #search {
        width: 100%;
      }
      
      #grid-view {
        grid-template-columns: 1fr;
      }
      
      .social-sharing {
        justify-content: center;
      }
      
      .footer-content,
      .footer-legal {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }
      
      .site-footer {
        padding: 1.5rem 1rem;
      }
    }
    #contact-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

#contact-form input,
#contact-form textarea {
  padding: 10px;
  border: 1px solid var(--border-color);
  border-radius: 4px;
  background: var(--card-bg);
  color: var(--text-color);
}

#contact-form button {
  background: var(--primary-color);
  color: white;
  border: none;
  padding: 10px;
  border-radius: 4px;
  cursor: pointer;
}

#contact-form button:hover {
  background: var(--primary-hover);
}

#contact-status {
  margin-top: 10px;
  font-size: 14px;
}

  </style>
</head>
<body>
  <!-- Skip to content link for accessibility -->
  <a href="#main-content" class="skip-to-content">Skip to content</a>
  
  <div class="container">
    <h1 id="page-title">🕵️ OSINT Tool Explorer
      <button class="keyboard-help" aria-label="Show keyboard shortcuts" id="show-keyboard-help">
        <i class="fas fa-keyboard"></i>
      </button>
    </h1>
    
    <div class="description">
      <p><strong>Welcome to the OSINT Tool Explorer</strong> - A comprehensive directory of <span class="seo-highlight">Open Source Intelligence (OSINT) tools</span> organized by category. This interactive explorer allows you to discover, search, and access a wide range of <span class="seo-highlight">OSINT resources</span> for investigations, research, and digital intelligence gathering.</p>
      
      <div class="seo-rich-content">
        <h2>What is OSINT?</h2>
        <p>Open Source Intelligence (OSINT) refers to the collection and analysis of information from publicly available sources for intelligence purposes. OSINT tools help investigators, researchers, security professionals, and journalists gather, process, and analyze data from various public sources including social media, websites, public records, and more.</p>
        
        <h2>How to Use This Directory</h2>
        <p>Browse tools by category in the tree view, or switch to grid view for a visual overview. Use the search function to find specific tools or categories. Each tool includes a description, type information, and direct access links.</p>
      </div>
    </div>
    
    <!-- Social sharing buttons -->
    <div class="social-sharing" aria-label="Share this tool">
      <span id="share-text">Share this tool: </span>
      <button class="social-btn twitter-btn" aria-label="Share on Twitter" title="Share on Twitter" onclick="shareOn('twitter')">
        <i class="fab fa-twitter"></i>
        <span class="sr-only">Twitter</span>
      </button>
      <button class="social-btn facebook-btn" aria-label="Share on Facebook" title="Share on Facebook" onclick="shareOn('facebook')">
        <i class="fab fa-facebook-f"></i>
        <span class="sr-only">Facebook</span>
      </button>
      <button class="social-btn linkedin-btn" aria-label="Share on LinkedIn" title="Share on LinkedIn" onclick="shareOn('linkedin')">
        <i class="fab fa-linkedin-in"></i>
        <span class="sr-only">LinkedIn</span>
      </button>
      <button class="social-btn reddit-btn" aria-label="Share on Reddit" title="Share on Reddit" onclick="shareOn('reddit')">
        <i class="fab fa-reddit-alien"></i>
        <span class="sr-only">Reddit</span>
      </button>
    </div>
    
    <div class="toolbar" role="toolbar" aria-label="Tool controls">
      <button class="toggle-theme" aria-label="Toggle dark mode">
        <i class="fas fa-moon"></i> <span class="theme-text">Toggle Dark Mode</span>
      </button>
      
      <div class="view-toggle" role="radiogroup" aria-label="View options">
        <button class="view-btn active" data-view="tree" role="radio" aria-checked="true" aria-label="Tree view">
          <i class="fas fa-sitemap"></i> Tree
        </button>
        <button class="view-btn" data-view="grid" role="radio" aria-checked="false" aria-label="Grid view">
          <i class="fas fa-th"></i> Grid
        </button>
      </div>
      
      <div class="search-container" role="search">
        <label for="search" class="sr-only">Search tools or categories</label>
        <input type="text" id="search" placeholder="🔍 Search tools or categories..." aria-label="Search tools or categories">
      </div>
      
      <div class="tool-stats" aria-live="polite">
        Total: <span id="total-categories"><?php echo isset($totalCategories) ? $totalCategories : 0; ?></span> categories, 
        <span id="total-tools"><?php echo isset($totalTools) ? $totalTools : 0; ?></span> tools
      </div>
    </div>
    
    <main id="main-content">
      <div id="tree" role="tree" aria-label="OSINT tools organized in a tree structure" tabindex="0"></div>
      <div id="grid-view" role="grid" aria-label="OSINT tools organized in a grid"></div>
      <div id="status" role="status" aria-live="polite"></div>
    </main>
  </div>
  
  <!-- Back to top button -->
  <button class="back-to-top" id="back-to-top" aria-label="Back to top">
    <i class="fas fa-arrow-up"></i>
  </button>
  
  <!-- Keyboard shortcuts help dialog -->
  <div class="overlay" id="keyboard-overlay"></div>
  <div class="keyboard-help-dialog" id="keyboard-help-dialog" role="dialog" aria-labelledby="dialog-title" aria-modal="true">
    <h2 id="dialog-title">Keyboard Shortcuts</h2>
    <div class="keyboard-shortcuts-list">
      <div class="keyboard-shortcut">
        <span>Search tools</span>
        <span class="key">/</span>
      </div>
      <div class="keyboard-shortcut">
        <span>Toggle view (Tree/Grid)</span>
        <span class="key">V</span>
      </div>
      <div class="keyboard-shortcut">
        <span>Toggle dark mode</span>
        <span class="key">D</span>
      </div>
      <div class="keyboard-shortcut">
        <span>Show keyboard shortcuts</span>
        <span class="key">?</span>
      </div>
      <div class="keyboard-shortcut">
        <span>Close dialogs</span>
        <span class="key">ESC</span>
      </div>
      <div class="keyboard-shortcut">
        <span>Navigate tree items</span>
        <span class="key">↑ ↓</span>
      </div>
      <div class="keyboard-shortcut">
        <span>Expand/collapse tree item</span>
        <span class="key">→ ←</span>
      </div>
      <div class="keyboard-shortcut">
        <span>Open selected tool</span>
        <span class="key">Enter</span>
      </div>
      <div class="keyboard-shortcut">
        <span>Back to top</span>
        <span class="key">Home</span>
      </div>
    </div>
    <button id="close-keyboard-help" aria-label="Close dialog">Close</button>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/jstree.min.js"></script>
  <script>
    // PHP generated JSON data
    const rawData = <?php echo json_encode($treeData); ?>;
    const toolTypes = <?php echo json_encode($toolTypes); ?>;
    const categoryColors = <?php echo json_encode($categoryColors); ?>;
    
    // Function to show status messages
    function showStatus(message, type) {
      const status = document.getElementById('status');
      status.textContent = message;
      status.className = type;
      status.setAttribute('aria-hidden', 'false');
      setTimeout(() => {
        status.style.display = 'none';
        status.className = '';
        status.setAttribute('aria-hidden', 'true');
      }, 5000);
    }

    // Function to copy text to clipboard
    function copyToClipboard(text) {
      navigator.clipboard.writeText(text).then(() => {
        showStatus("URL copied to clipboard!", "success");
      }).catch(err => {
        showStatus("Failed to copy: " + err, "error");
        console.error('Could not copy text: ', err);
      });
    }
    
    // Social sharing functions
    function shareOn(platform) {
      const url = encodeURIComponent(window.location.href);
      const title = encodeURIComponent(document.title);
      let shareUrl = '';
      
      switch(platform) {
        case 'twitter':
          shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
          break;
        case 'facebook':
          shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
          break;
        case 'linkedin':
          shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
          break;
        case 'reddit':
          shareUrl = `https://www.reddit.com/submit?url=${url}&title=${title}`;
          break;
      }
      
      if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400,noopener,noreferrer');
      }
    }

    // Function to share a specific tool on social media
    function shareToolOn(platform, toolName, toolUrl) {
      const title = encodeURIComponent(`Check out this OSINT tool: ${toolName}`);
      const url = encodeURIComponent(toolUrl);
      let shareUrl = '';
      
      switch(platform) {
        case 'twitter':
          shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
          break;
        case 'facebook':
          shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
          break;
        case 'linkedin':
          shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
          break;
        case 'reddit':
          shareUrl = `https://www.reddit.com/submit?url=${url}&title=${title}`;
          break;
      }
      
      if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400,noopener,noreferrer');
        return false; // Prevent event bubbling
      }
    }

    function convertToJsTree(node) {
      const isLeaf = !node.children || node.children.length === 0;

      let item = {
        text: node.name,
        state: { opened: node.state?.opened || false }
      };

      // Add color data for category nodes
      if (node.color) {
        item.data = item.data || {};
        item.data.color = node.color;
        
        // Count number of child items for folder count
        const childCount = node.children ? node.children.length : 0;
        const countBadge = `<span class="count-badge" style="background-color:${node.color};color:white;font-size:11px;padding:1px 6px;border-radius:10px;margin-left:8px;" aria-label="${childCount} tools">${childCount}</span>`;
        
        // Add color indicator and count to category nodes
        item.text = `<span style="color:${node.color}">⬤</span> ${node.name} ${countBadge}`;
      }

      if (isLeaf && node.url) {
        const domain = node.url.startsWith('http') ? new URL(node.url).hostname : 'example.com';
        // Store URL and description as data attributes
        item.data = item.data || {}; 
        item.data.url = node.url;
        item.data.description = node.description || node.name;
        item.data.toolType = node.toolType || 'free';
        item.data.name = node.name;
        
        // Add the tool type tag
        const toolTypeLabel = toolTypes[node.toolType] || '';
        const toolTypeSpan = toolTypeLabel ? 
          `<span class="tool-type-tag" style="font-size:11px;background:#f0f0f0;padding:1px 4px;border-radius:3px;margin-left:5px;">${toolTypeLabel}</span>` : '';
        
        // Add buttons (copy URL, share)
        const actionButtons = `<span class="tool-actions">
          <button class="copy-url-btn" title="Copy URL" aria-label="Copy URL" data-url="${node.url}">
            <i class="fas fa-copy"></i>
          </button>
          <div class="tool-share-dropdown">
            <button class="share-tool-btn" title="Share" aria-label="Share tool" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-share-alt"></i>
            </button>
            <div class="share-dropdown-content" role="menu" hidden>
              <button role="menuitem" class="share-twitter" aria-label="Share on Twitter" onclick="shareToolOn('twitter', '${node.name.replace(/'/g, "\\'")}', '${node.url.replace(/'/g, "\\'")}')">
                <i class="fab fa-twitter"></i> Twitter
              </button>
              <button role="menuitem" class="share-facebook" aria-label="Share on Facebook" onclick="shareToolOn('facebook', '${node.name.replace(/'/g, "\\'")}', '${node.url.replace(/'/g, "\\'")}')">
                <i class="fab fa-facebook"></i> Facebook
              </button>
              <button role="menuitem" class="share-linkedin" aria-label="Share on LinkedIn" onclick="shareToolOn('linkedin', '${node.name.replace(/'/g, "\\'")}', '${node.url.replace(/'/g, "\\'")}')">
                <i class="fab fa-linkedin"></i> LinkedIn
              </button>
            </div>
          </div>
        </span>`;
        
        item.text = `${node.label || node.name}${toolTypeSpan}${actionButtons}`;
        item.icon = "https://www.google.com/s2/favicons?domain=" + domain;
        
        // Add a class to identify this as a clickable node
        item.li_attr = { 
          class: 'clickable-node', 
          tabindex: '0', 
          'data-url': node.url,
          'aria-label': `${node.name}, ${node.description || 'No description'}`
        };
      }

      if (node.children && Array.isArray(node.children)) {
        item.children = node.children.map(convertToJsTree);
      }

      return item;
    }

    const treeData = [convertToJsTree(rawData)];

    // Process all data to build grid view 
    function buildGridView(data, parentCategory = null, parentColor = null) {
      let tools = [];
      
      function processNode(node, category, categoryColor) {
        if (node.children && node.children.length > 0) {
          // Extract category name (remove the icon/symbol if present)
          let categoryName = node.name;
          if (categoryName.includes(' ')) {
            categoryName = categoryName.split(' ').slice(1).join(' ');
          }
          
          // Get category color from data if available
          let color = node.color || categoryColor || '#666666';
          
          node.children.forEach(child => {
            processNode(child, categoryName, color);
          });
        } else if (node.url) {
          tools.push({
            name: node.name,
            url: node.url,
            description: node.description || '',
            category: category || 'Uncategorized',
            categoryColor: categoryColor || '#666666',
            toolType: node.toolType || 'free',
            domain: node.url.startsWith('http') ? new URL(node.url).hostname : 'example.com'
          });
        }
      }
      
      processNode(data, parentCategory, parentColor);
      return tools;
    }
    
    // Create tool cards for grid view
    function createToolCards(tools) {
      const gridView = document.getElementById('grid-view');
      gridView.innerHTML = '';
      
      tools.forEach(tool => {
        const card = document.createElement('div');
        card.className = 'tool-card';
        card.setAttribute('tabindex', '0');
        card.setAttribute('data-url', tool.url);
        card.setAttribute('role', 'gridcell');
        card.setAttribute('aria-label', `${tool.name}, ${tool.category}`);
        
        const typeLabel = toolTypes[tool.toolType] || 'Tool';
        
        card.innerHTML = `
          <div class="tool-card-header">
            <img class="tool-card-icon" src="https://www.google.com/s2/favicons?domain=${tool.domain}" alt="" aria-hidden="true">
            <div class="tool-card-title">${tool.name}</div>
            <div class="tool-card-type">${typeLabel}</div>
          </div>
          <div class="tool-card-content">
            <div class="tool-card-description">${tool.description || 'No description available'}</div>
            <div class="tool-card-actions">
              <div class="tool-card-category" style="background-color:${tool.categoryColor}">${tool.category}</div>
              <div class="tool-card-buttons">
                <button class="open-tool-btn" title="Open tool" aria-label="Open ${tool.name}">
                  <i class="fas fa-external-link-alt" aria-hidden="true"></i> Open
                </button>
                <button class="copy-url-btn" title="Copy URL" aria-label="Copy URL for ${tool.name}" data-url="${tool.url}">
                  <i class="fas fa-copy" aria-hidden="true"></i> Copy
                </button>
              </div>
            </div>
            <div class="tool-card-social">
              <span>Share: </span>
              <button class="social-btn twitter-btn" aria-label="Share ${tool.name} on Twitter" title="Share on Twitter" 
                      onclick="shareToolOn('twitter', '${tool.name.replace(/'/g, "\\'")}', '${tool.url.replace(/'/g, "\\'")}')">
                <i class="fab fa-twitter" aria-hidden="true"></i>
              </button>
              <button class="social-btn facebook-btn" aria-label="Share ${tool.name} on Facebook" title="Share on Facebook"
                      onclick="shareToolOn('facebook', '${tool.name.replace(/'/g, "\\'")}', '${tool.url.replace(/'/g, "\\'")}')">
                <i class="fab fa-facebook-f" aria-hidden="true"></i>
              </button>
              <button class="social-btn linkedin-btn" aria-label="Share ${tool.name} on LinkedIn" title="Share on LinkedIn"
                      onclick="shareToolOn('linkedin', '${tool.name.replace(/'/g, "\\'")}', '${tool.url.replace(/'/g, "\\'")}')">
                <i class="fab fa-linkedin-in" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        `;
        
        gridView.appendChild(card);
      });
      
      // Add event listeners for cards
      document.querySelectorAll('.tool-card').forEach(card => {
        // Open on click
        card.querySelector('.open-tool-btn').addEventListener('click', (e) => {
          e.stopPropagation();
          const url = card.getAttribute('data-url');
          window.open(url, '_blank', 'noopener,noreferrer');
          showStatus(`Opening: ${url}`, 'success');
        });
        
        // Open on Enter key
        card.addEventListener('keydown', (e) => {
          if (e.key === 'Enter') {
            const url = card.getAttribute('data-url');
            window.open(url, '_blank', 'noopener,noreferrer');
            showStatus(`Opening: ${url}`, 'success');
          }
        });
        
        // Copy URL
        card.querySelector('.copy-url-btn').addEventListener('click', (e) => {
          e.stopPropagation();
          const url = e.currentTarget.getAttribute('data-url');
          copyToClipboard(url);
        });
      });
    }

    // Initialize UI and handle events
    $(function() {
      // Initialize the tree
      const $tree = $('#tree').jstree({
        core: { 
          data: treeData, 
          check_callback: true,
          themes: {
            responsive: true
          }
        },
        plugins: ["search", "wholerow"],
        search: {
          show_only_matches: true,
          show_only_matches_children: true
        }
      });
      
      // Build grid view with all tools
      const allTools = buildGridView(rawData);
      createToolCards(allTools);
      
      // Keyboard help dialog functions
      const keyboardHelpBtn = document.getElementById('show-keyboard-help');
      const keyboardHelpDialog = document.getElementById('keyboard-help-dialog');
      const closeKeyboardHelpBtn = document.getElementById('close-keyboard-help');
      const overlay = document.getElementById('keyboard-overlay');
      
      function showKeyboardHelp() {
        keyboardHelpDialog.classList.add('visible');
        overlay.classList.add('visible');
        closeKeyboardHelpBtn.focus();
      }
      
      function hideKeyboardHelp() {
        keyboardHelpDialog.classList.remove('visible');
        overlay.classList.remove('visible');
        keyboardHelpBtn.focus();
      }
      
      keyboardHelpBtn.addEventListener('click', showKeyboardHelp);
      closeKeyboardHelpBtn.addEventListener('click', hideKeyboardHelp);
      overlay.addEventListener('click', hideKeyboardHelp);
      
      // Back to top button
      const backToTopBtn = document.getElementById('back-to-top');
      
      function toggleBackToTopBtn() {
        if (window.scrollY > 300) {
          backToTopBtn.classList.add('visible');
        } else {
          backToTopBtn.classList.remove('visible');
        }
      }
      
      function scrollToTop() {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      }
      
      window.addEventListener('scroll', toggleBackToTopBtn);
      backToTopBtn.addEventListener('click', scrollToTop);
      
      // Handle share dropdown toggle
      $(document).on('click', '.share-tool-btn', function(e) {
        e.stopPropagation();
        const dropdown = $(this).next('.share-dropdown-content');
        const isHidden = dropdown.is('[hidden]');
        
        // Hide all other dropdowns
        $('.share-dropdown-content').attr('hidden', 'hidden');
        $('.share-tool-btn').attr('aria-expanded', 'false');
        
        // Toggle this dropdown
        if (isHidden) {
          dropdown.removeAttr('hidden');
          $(this).attr('aria-expanded', 'true');
        } else {
          dropdown.attr('hidden', 'hidden');
          $(this).attr('aria-expanded', 'false');
        }
      });
      
      // Close dropdowns when clicking elsewhere
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.tool-share-dropdown').length) {
          $('.share-dropdown-content').attr('hidden', 'hidden');
          $('.share-tool-btn').attr('aria-expanded', 'false');
        }
      });
      
      // Handle copy URL button clicks
      $(document).on('click', '.copy-url-btn', function(e) {
        e.stopPropagation();
        const url = $(this).data('url');
        copyToClipboard(url);
      });

      // Handle clicks on tree nodes
      $('#tree').on('click.jstree', '.jstree-anchor', function(e) {
        // Ignore clicks on the action buttons
        if ($(e.target).closest('.tool-actions').length) {
          return;
        }
        
        const nodeId = $(this).closest('li').attr('id');
        const node = $('#tree').jstree(true).get_node(nodeId);
        
        // If this node has a URL, open it
        if (node && node.data && node.data.url) {
          try {
            window.open(node.data.url, '_blank', 'noopener,noreferrer');
            showStatus(`Opening: ${node.data.url}`, 'success');
          } catch (error) {
            showStatus(`Error opening link: ${error.message}`, 'error');
            console.error('Error opening URL:', error);
          }
          e.stopPropagation();
          return false;
        }
      });
      
      // Keyboard navigation for tree
      $('#tree').on('keydown', '.clickable-node', function(e) {
        if (e.key === 'Enter') {
          const url = $(this).data('url');
          if (url) {
            window.open(url, '_blank', 'noopener,noreferrer');
            showStatus(`Opening: ${url}`, 'success');
          }
        }
      });
      
      // Global keyboard shortcuts
      $(document).on('keydown', function(e) {
        // Don't trigger shortcuts when typing in input fields
        if ($(e.target).is('input, textarea')) {
          return;
        }
        
        switch(e.key) {
          case '/': // Focus search box
            e.preventDefault();
            $('#search').focus();
            break;
            
          case 'v': // Toggle view
          case 'V':
            e.preventDefault();
            const currentView = $('.view-btn.active').data('view');
            const newView = currentView === 'tree' ? 'grid' : 'tree';
            $(`.view-btn[data-view="${newView}"]`).click();
            break;
            
          case 'd': // Toggle dark mode
          case 'D':
            e.preventDefault();
            $('.toggle-theme').click();
            break;
            
          case '?': // Show keyboard shortcuts
            e.preventDefault();
            showKeyboardHelp();
            break;
            
          case 'Escape': // Close dialogs
            if (keyboardHelpDialog.classList.contains('visible')) {
              hideKeyboardHelp();
            }
            break;
            
          case 'Home': // Back to top
            e.preventDefault();
            scrollToTop();
            break;
        }
      });

      // Live search with debounce
      let to = false;
      $('#search').on("keyup", function () {
        if (to) clearTimeout(to);
        to = setTimeout(() => {
          const searchTerm = $(this).val();
          
          // Tree view search
          $('#tree').jstree(true).search(searchTerm);
          
          // Grid view search (simple filtering)
          if (searchTerm) {
            const searchLower = searchTerm.toLowerCase();
            const filteredTools = allTools.filter(tool => 
              tool.name.toLowerCase().includes(searchLower) || 
              tool.description.toLowerCase().includes(searchLower) ||
              tool.category.toLowerCase().includes(searchLower)
            );
            createToolCards(filteredTools);
            // Announce search results to screen readers
            const resultCount = filteredTools.length;
            showStatus(`Found ${resultCount} tool${resultCount === 1 ? '' : 's'} matching "${searchTerm}"`, "success");
          } else {
            createToolCards(allTools);
          }
        }, 250);
      });

      // Toggle view (tree/grid)
      $('.view-btn').on('click', function() {
        const view = $(this).data('view');
        $('.view-btn').removeClass('active');
        $(this).addClass('active');
        $('.view-btn').attr('aria-checked', 'false');
        $(this).attr('aria-checked', 'true');
        
        if (view === 'tree') {
          $('#tree').show();
          $('#grid-view').hide();
          $('#tree').attr('aria-hidden', 'false');
          $('#grid-view').attr('aria-hidden', 'true');
        } else {
          $('#tree').hide();
          $('#grid-view').show();
          $('#tree').attr('aria-hidden', 'true');
          $('#grid-view').attr('aria-hidden', 'false');
        }
      });

      // Dark mode toggle
      $('.toggle-theme').on('click', function () {
        document.body.classList.toggle('dark');
        const isDark = document.body.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        
        // Update icon and text
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
        
        // Announce theme change to screen readers
        showStatus(`Theme switched to ${isDark ? 'dark' : 'light'} mode`, "success");
      });

      // Persist theme preference
      if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark');
        $('.toggle-theme i').removeClass('fa-moon').addClass('fa-sun');
        $('.toggle-theme .theme-text').text('Toggle Light Mode');
        $('.toggle-theme').attr('aria-label', 'Toggle light mode');
      }
    });
  </script>
  
  <!-- Structured Data for SEO -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "OSINT Tool Explorer",
    "description": "A comprehensive directory of Open Source Intelligence (OSINT) tools organized by category",
    "applicationCategory": "SecurityApplication",
    "operatingSystem": "Any",
    "offers": {
      "@type": "Offer",
      "price": "0",
      "priceCurrency": "USD"
    },
    "author": {
      "@type": "Organization",
      "name": "OSINT Explorer Team",
      "url": "https://example.com"
    }
  }
  </script>
  
  <!-- Footer with legal information -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section about">
          <h3>About OSINT Tool Explorer</h3>
          <p>The OSINT Tool Explorer is a comprehensive directory designed to help researchers, investigators, and security professionals discover and access Open Source Intelligence tools.</p>
        </div>
        

        
        <div class="footer-section contact">

  <h3>Contact Us</h3>
  <p>Have questions or suggestions? We'd love to hear from you!</p>
  <a href="contact.php" class="contact-link" style="display: inline-block; margin-top: 10px; padding: 10px 15px; background: var(--primary-color); color: white; text-decoration: none; border-radius: 4px;">
    <i class="fas fa-envelope"></i> Contact Form
  </a>
</div>
          <div class="social-links">
            <a href="#" aria-label="Twitter page"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="GitHub page"><i class="fab fa-github"></i></a>
            <a href="#" aria-label="LinkedIn page"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>
      </div>
      
      <div class="footer-legal">
        <div class="disclaimer">
          <h4>Disclaimer</h4>
          <p>The OSINT Tool Explorer is provided "as is", without warranty of any kind, express or implied. The operators of this directory do not endorse or take responsibility for any tools listed or their usage. Users are solely responsible for how they use these tools and must comply with all applicable laws and regulations.</p>
        </div>
        
        <div class="hold-harmless">
          <h4>Hold Harmless Agreement</h4>
          <p>By using the OSINT Tool Explorer, you agree to hold harmless and indemnify the operators, contributors, and maintainers of this directory from and against any claims, actions, suits, damages, liabilities, costs, charges, or expenses arising from your use of any tools or information provided through this directory.</p>
        </div>
        
        <div class="usage-policy">
          <h4>Acceptable Use Policy</h4>
          <p>All tools listed in this directory are intended for legal and ethical use only. The use of these tools for unauthorized access, privacy invasion, harassment, illegal surveillance, or any other illicit activity is strictly prohibited and may result in civil and/or criminal penalties.</p>
        </div>
      </div>
      
      <div class="copyright">
        <p>&copy; <span id="current-year">2025</span> OSINT Tool Explorer. All rights reserved.</p>
        <script>document.getElementById('current-year').textContent = new Date().getFullYear();</script>
      </div>
    </div>
  </footer>
</body>
</html>