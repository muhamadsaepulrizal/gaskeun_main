const fs = require('fs');
const path = require('path');

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(function(file) {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) { 
            results = results.concat(walk(file));
        } else { 
            if(file.endsWith('.blade.php')) results.push(file);
        }
    });
    return results;
}

const files = walk('c:/laragon/www/gaskeun-main/resources/views');
let updatedCount = 0;

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    
    const regex1 = /<p[^>]*class=['"]text-xs font-mono mb-1['"][^>]*style=['"]color:#10B981;\s*letter-spacing:0\.1em;['"][^>]*>\s*\/\/\s*(.*?)\s*<\/p>/gi;
    
    let changed = false;
    
    if (regex1.test(content)) {
        content = content.replace(regex1, '<div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#e6f2ef] text-[#0B5240] border border-[#14765C]/20 text-[10px] font-bold tracking-widest uppercase mb-3 shadow-sm"><i class="fa-solid fa-tags"></i> $1</div>');
        changed = true;
    }
    
    if (changed) {
        fs.writeFileSync(file, content, 'utf8');
        updatedCount++;
        console.log('Updated: ' + file);
    }
});

console.log(`Updated ${updatedCount} files.`);
