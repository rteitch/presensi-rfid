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

const newScript = `<script>
        function filterTableRows(query) {
            const q = query.toLowerCase().trim();
            const rows = document.querySelectorAll('table tbody tr:not(.empty-row)');
            let visibleCount = 0;
            rows.forEach(row => {
                if (!row.querySelector('td[colspan]')) {
                    const text = row.innerText.toLowerCase();
                    if (text.includes(q)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
            
            let emptyRow = document.querySelector('table tbody tr.empty-row');
            if (!emptyRow) {
                const tbody = document.querySelector('table tbody');
                const theadTr = document.querySelector('table thead tr');
                const cols = theadTr ? theadTr.children.length : 1;
                emptyRow = document.createElement('tr');
                emptyRow.className = 'empty-row';
                emptyRow.innerHTML = \`<td colspan="\${cols}" class="py-12 text-center text-slate-400">
                    <div class="flex flex-col items-center gap-2">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-sm font-medium">Pencarian tidak ditemukan.</span>
                    </div>
                </td>\`;
                tbody.appendChild(emptyRow);
            }
            emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }
    </script>`;

for (const file of indexFiles) {
    const fullPath = path.join(viewsDir, file);
    if (fs.existsSync(fullPath)) {
        let content = fs.readFileSync(fullPath, 'utf8');

        // Fix clear icon position
        content = content.replace(/class="absolute right-3\.5 text-slate-400 hover:text-rose-500 transition/g, 
                                  'style="top: 50%; transform: translateY(-50%); right: 14px;" class="absolute text-slate-400 hover:text-rose-500 transition');

        // Fix search icon position if not already having inline styles
        content = content.replace(/<svg\s+style="width:16px;height:16px"\s+class="w-4 h-4 absolute left-3\.5/g, 
                                  '<svg style="width:16px;height:16px; top: 50%; transform: translateY(-50%);" class="w-4 h-4 absolute left-3.5');

        // Replace filterTableRows
        const scriptRegex = /<script>\s*function filterTableRows[\s\S]*?<\/script>/;
        if (scriptRegex.test(content)) {
            content = content.replace(scriptRegex, newScript);
        } else if (content.includes('filterTableRows(this.value)')) {
            // Append script at the end before </x-app-layout>
            content = content.replace(/<\/x-app-layout>/, newScript + '\n</x-app-layout>');
        }

        fs.writeFileSync(fullPath, content);
        console.log('Patched', file);
    }
}
