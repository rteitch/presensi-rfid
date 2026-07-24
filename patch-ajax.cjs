const fs = require('fs');
const path = require('path');

const viewsDir = path.join(__dirname, 'resources', 'views');
const indexFiles = [
    'users/index.blade.php',
    'students/index.blade.php',
    'teachers/index.blade.php',
    'devices/index.blade.php',
    'classes/index.blade.php',
    'integrations/index.blade.php'
];

for (const file of indexFiles) {
    const fullPath = path.join(viewsDir, file);
    if (fs.existsSync(fullPath)) {
        let content = fs.readFileSync(fullPath, 'utf8');

        // 1. Change oninput to use performAjaxSearch
        content = content.replace(/window\.searchTimer=setTimeout\(\(\) => this\.form\.submit\(\),\s*800\)/g, 
                                  'window.searchTimer=setTimeout(() => window.performAjaxSearch(this.form), 500)');

        // 2. Remove onkeyup="filterTableRows(this.value)"
        content = content.replace(/\s*onkeyup="filterTableRows\(this\.value\)"/g, '');

        // 3. Remove the entire <script> block for filterTableRows
        const scriptRegex = /<script>\s*function filterTableRows[\s\S]*?<\/script>\s*/;
        content = content.replace(scriptRegex, '');

        // 4. Update <select name="role"> if it has onchange="this.form.submit()"
        content = content.replace(/onchange="this\.form\.submit\(\)"/g, 'onchange="window.performAjaxSearch(this.form)"');
        
        fs.writeFileSync(fullPath, content);
        console.log('Patched for AJAX:', file);
    }
}
