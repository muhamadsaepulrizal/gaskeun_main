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
        
        # 1. Upgrade Card Wrapper
        if '<div class="card p-8 max-w-xl">' in content:
            content = content.replace(
                '<div class="card p-8 max-w-xl">',
                '<div class="max-w-2xl mx-auto">\n<div class="card p-8 md:p-10 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">'
            )
            # Add closing div before @endsection
            content = content.replace('</div>\n@endsection', '</div>\n</div>\n@endsection')

        # 2. Add icons to Simpan/Update buttons
        if '<button type="submit" class="btn-primary">Simpan</button>' in content:
            content = content.replace(
                '<button type="submit" class="btn-primary">Simpan</button>',
                '<button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i> Simpan</button>'
            )
        if '<button type="submit" class="btn-primary">Update</button>' in content:
            content = content.replace(
                '<button type="submit" class="btn-primary">Update</button>',
                '<button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i> Update</button>'
            )

        # 3. Add styling to inputs
        # For selects (usually wrapped in <div class="relative mt-1">)
        # We'll just change the select-field class to have pl-11
        if 'class="select-field pr-10"' in content:
            content = content.replace('class="select-field pr-10"', 'class="select-field pl-11 pr-10"')
            # Inject an icon before the select
            content = re.sub(
                r'(<select[^>]*class="select-field pl-11 pr-10"[^>]*>)',
                r'<span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors"><i class="fa-solid fa-list"></i></span>\n                \1',
                content
            )
            # Make sure the parent is group
            content = content.replace('<div class="relative mt-1">', '<div class="relative mt-1 group">')

        # For text inputs (usually NOT wrapped in relative)
        if 'class="input-field mt-1"' in content:
            content = content.replace('class="input-field mt-1"', 'class="input-field pl-11 mt-1"')
            content = re.sub(
                r'(<input[^>]*class="input-field pl-11 mt-1"[^>]*>)',
                r'<div class="relative group mt-1">\n                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand transition-colors"><i class="fa-solid fa-pen"></i></span>\n                \1\n            </div>',
                content
            )

        # Remove the old mt-1 on the input since the wrapper has it now
        if 'class="input-field pl-11 mt-1"' in content:
            content = re.sub(r'class="input-field pl-11 mt-1"', 'class="input-field pl-11 w-full focus:ring-2 focus:ring-brand/20 transition-all"', content)

        if content != original_content:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            files_modified += 1
            
    except Exception as e:
        print(f"Error processing {filepath}: {e}")

print(f"Forms modernized. Modified {files_modified} files.")
