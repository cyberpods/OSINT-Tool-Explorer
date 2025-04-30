# OSINT Tool Explorer

![OSINT Tool Explorer Screenshot](https://via.placeholder.com/800x400?text=OSINT+Tool+Explorer+Screenshot)

A comprehensive, interactive directory of Open Source Intelligence (OSINT) tools sourced from the awesome [jivoi/awesome-osint](https://github.com/jivoi/awesome-osint) repository.

## Features

- 🕵️ **Interactive Explorer**: Browse OSINT tools in tree or grid view
- 🔍 **Smart Search**: Quickly find tools by name, description, or category
- 🌓 **Dark/Light Mode**: Toggle between color schemes for comfortable viewing
- 📱 **Responsive Design**: Works on desktop and mobile devices
- 📋 **Tool Details**: View descriptions and direct links to each tool
- 🔗 **Easy Sharing**: Share tools via social media or copy URLs
- ♿ **Accessible**: Built with accessibility best practices

## Components

### `getdata.py`

This Python script fetches and processes the OSINT tools data from the awesome-osint repository:

```python
def fetch_readme():
    # Fetches the README.md from jivoi/awesome-osint
    url = 'https://raw.githubusercontent.com/jivoi/awesome-osint/master/README.md'
    response = requests.get(url)
    return response.text

def parse_markdown(md_text):
    # Parses the markdown into structured data
    osint_data = {}
    current_category = None
    
    # Processes headers and tool links
    for line in md_text.splitlines():
        # Category detection
        if header_match := re.match(r'^(#{2,3})\s+(.*)', line):
            current_category = header_match.group(2).strip()
            osint_data[current_category] = []
            
        # Tool link processing
        if link_match := re.match(r'^\s*[-*]\s+\[(.+?)\]\((http.*?)\)', line):
            name = link_match.group(1).strip()
            url = link_match.group(2).strip()
            description = link_match.group(3).strip() if link_match.group(3) else ''
            osint_data[current_category].append({
                'name': name,
                'url': url,
                'description': description
            })
    
    return osint_data
```

**Key Features:**
- Automatically fetches the latest data from awesome-osint
- Parses markdown structure into JSON format
- Handles categories and subcategories
- Extracts tool names, URLs, and descriptions
- Outputs clean JSON data for the web interface

### `index.php`

The main web interface that displays the OSINT tools in an interactive explorer:

**Key Features:**
- Dynamic tree view with collapsible categories
- Grid view for visual browsing
- Advanced search functionality
- Dark/light mode toggle
- Keyboard navigation support
- Social sharing capabilities
- Responsive design for all devices
- Accessibility features (ARIA labels, keyboard controls)

```php
// Example of how the data is processed for display
$treeData = [
    "name" => "OSINT", 
    "state" => ["opened" => true],
    "children" => array_map(function($category, $tools) {
        return [
            "name" => $category,
            "children" => array_map(function($tool) {
                return [
                    "name" => $tool['name'],
                    "url" => $tool['url'],
                    "description" => $tool['description'] ?? ''
                ];
            }, $tools)
        ];
    }, array_keys($rawData), array_values($rawData))
];
```

## Data Source

All tool data comes from the comprehensive [awesome-osint](https://github.com/jivoi/awesome-osint) repository maintained by @jivoi. This project provides an interactive interface to explore that valuable collection of resources.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/osint-tool-explorer.git
   cd osint-tool-explorer
   ```

2. Run the data fetcher:
   ```bash
   python getdata.py
   ```

3. Set up a web server (Apache, Nginx, etc.) to serve the PHP files

4. Access the interface at `http://localhost/index.php`

## Requirements

- Python 3.x (for `getdata.py`)
- PHP 7.0+ (for web interface)
- Web server (Apache, Nginx, etc.)

## Contributing

Contributions are welcome! Please open issues or pull requests for:
- New features
- Bug fixes
- UI improvements
- Additional data processing

## License

MIT License

## Screenshots

![Tree View](https://via.placeholder.com/400x300?text=Tree+View)
![Grid View](https://via.placeholder.com/400x300?text=Grid+View)
![Dark Mode](https://via.placeholder.com/400x300?text=Dark+Mode)

---

This project provides an interactive interface to explore the comprehensive collection of OSINT tools from [awesome-osint](https://github.com/jivoi/awesome-osint). The data is automatically fetched and processed, ensuring you always have access to the latest tools and resources.
