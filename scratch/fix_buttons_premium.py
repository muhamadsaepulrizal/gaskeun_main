import os
import glob
import re

views_dir = r'c:\laragon\www\gaskeun-main\resources\views'
blade_files = glob.glob(os.path.join(views_dir, '**', '*.blade.php'), recursive=True)

files_modified = 0

# These are the exact long strings my previous script injected
# I need to reverse them!
btn_primary_classes = 'inline-flex justify-center items-center rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-600 transition-colors'
btn_secondary_classes = 'inline-flex justify-center items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors'

for filepath in blade_files:
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
            
        original_content = content
        
        # Replace the long classes back with btn-primary / btn-secondary
        content = content.replace(f'class="{btn_primary_classes} shrink-0"', 'class="btn-primary shrink-0"')
        content = content.replace(f'class="{btn_primary_classes}"', 'class="btn-primary"')
        content = content.replace(f'class=\'{btn_primary_classes}\'', 'class="btn-primary"')
        
        content = content.replace(f'class="{btn_secondary_classes} shrink-0"', 'class="btn-secondary shrink-0"')
        content = content.replace(f'class="{btn_secondary_classes}"', 'class="btn-secondary"')
        content = content.replace(f'class=\'{btn_secondary_classes}\'', 'class="btn-secondary"')
        
        if content != original_content:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            files_modified += 1
            
    except Exception as e:
        print(f"Error processing {filepath}: {e}")

print(f"Button premium replacement complete. Modified {files_modified} files.")
