import os
import glob
import re

views_dir = r'c:\laragon\www\gaskeun-main\resources\views'
blade_files = glob.glob(os.path.join(views_dir, '**', '*.blade.php'), recursive=True)

files_modified = 0

btn_primary_explicit = 'inline-flex justify-center items-center rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#0B5240] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-brand/30 transition-all duration-200 transform hover:-translate-y-0.5'
btn_secondary_explicit = 'inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200'

for filepath in blade_files:
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
            
        original_content = content
        
        # Replace class="btn-primary" and class="btn-primary shrink-0" and single quotes
        # We also need to be careful not to replace if it's already replaced, but since it's exact match it's fine
        content = content.replace('class="btn-primary"', f'class="{btn_primary_explicit}"')
        content = content.replace("class='btn-primary'", f'class="{btn_primary_explicit}"')
        content = content.replace('class="btn-primary shrink-0"', f'class="{btn_primary_explicit} shrink-0"')
        
        # Replace class="btn-secondary" and class="btn-secondary shrink-0" and single quotes
        content = content.replace('class="btn-secondary"', f'class="{btn_secondary_explicit}"')
        content = content.replace("class='btn-secondary'", f'class="{btn_secondary_explicit}"')
        content = content.replace('class="btn-secondary shrink-0"', f'class="{btn_secondary_explicit} shrink-0"')

        if content != original_content:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            files_modified += 1
            
    except Exception as e:
        print(f"Error processing {filepath}: {e}")

print(f"Explicit buttons applied. Modified {files_modified} files.")
