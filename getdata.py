import requests
import json
import re

def fetch_readme():
    url = 'https://raw.githubusercontent.com/jivoi/awesome-osint/master/README.md'
    print("[*] Downloading README.md from GitHub...")
    response = requests.get(url)
    if response.status_code != 200:
        raise Exception(f"[!] Failed to fetch README.md: HTTP {response.status_code}")
    print("[+] Successfully fetched README.md")
    return response.text

def parse_markdown(md_text):
    osint_data = {}
    current_category = None
    lines = md_text.splitlines()

    print("[*] Parsing Markdown...")
    for i, line in enumerate(lines):
        # Detect category or subcategory headers (## or ###)
        header_match = re.match(r'^(#{2,3})\s+(.*)', line)
        if header_match:
            current_category = header_match.group(2).strip()
            osint_data[current_category] = []
            print(f"  [+] Found category: {current_category}")
            continue

        # Match links with optional description: - [name](url) - description
        link_match = re.match(r'^\s*[-*]\s+\[(.+?)\]\((http.*?)\)(?:\s*-\s*(.*))?', line)
        if link_match and current_category:
            name = link_match.group(1).strip()
            url = link_match.group(2).strip()
            description = link_match.group(3).strip() if link_match.group(3) else ''
            osint_data[current_category].append({
                'name': name,
                'url': url,
                'description': description
            })
            print(f"    [+] Added tool: {name}")

    print("[+] Markdown parsing complete.")
    return osint_data

def save_to_json(data, filename='awesome_osint.json'):
    print(f"[*] Saving data to {filename}...")
    with open(filename, 'w', encoding='utf-8') as f:
        json.dump(data, f, indent=2, ensure_ascii=False)
    print(f"[+] File saved: {filename}")

if __name__ == '__main__':
    try:
        markdown = fetch_readme()
        parsed_data = parse_markdown(markdown)
        save_to_json(parsed_data)
        print("[✓] All done!")
    except Exception as e:
        print(f"[X] Error: {e}")
