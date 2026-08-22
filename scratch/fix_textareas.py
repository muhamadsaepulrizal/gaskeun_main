import os
import glob
import re

views_dir = r'c:\laragon\www\gaskeun-main\resources\views'
blade_files = glob.glob(os.path.join(views_dir, '**', '*create.blade.php'), recursive=True) + \
              glob.glob(os.path.join(views_dir, '**', '*edit.blade.php'), recursive=True)

files_modified = 0

for filepath in blade_files:
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
            
        original_content = content
        
        # Remove pl-11 from textareas
        content = re.sub(
            r'(<textarea[^>]*class="[^"]*)pl-11([^"]*"[^>]*>)',
            r'\1\2',
            content
        )

        if content != original_content:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            files_modified += 1
            
    except Exception as e:
        print(f"Error processing {filepath}: {e}")

print(f"Textareas fixed. Modified {files_modified} files.")
