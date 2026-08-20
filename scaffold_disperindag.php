<?php

$entities = [
    'Kecamatan' => [
        'route' => 'kecamatans',
        'var' => 'kecamatan',
        'fields' => [
            ['name' => 'nama_kecamatan', 'label' => 'Nama Kecamatan', 'type' => 'text']
        ]
    ],
    'Desa' => [
        'route' => 'desas',
        'var' => 'desa',
        'with' => ['kecamatan'],
        'fields' => [
            ['name' => 'kecamatan_id', 'label' => 'Kecamatan', 'type' => 'select', 'relation' => 'App\Models\Kecamatan', 'display' => 'nama_kecamatan'],
            ['name' => 'nama_desa', 'label' => 'Nama Desa', 'type' => 'text']
        ]
    ],
    'Kk' => [
        'route' => 'kks',
        'var' => 'kk',
        'with' => ['desa'],
        'fields' => [
            ['name' => 'desa_id', 'label' => 'Desa', 'type' => 'select', 'relation' => 'App\Models\Desa', 'display' => 'nama_desa'],
            ['name' => 'nomor_kk', 'label' => 'Nomor KK', 'type' => 'text'],
            ['name' => 'alamat_lengkap', 'label' => 'Alamat Lengkap', 'type' => 'textarea']
        ]
    ],
    'Penduduk' => [
        'route' => 'penduduks',
        'var' => 'penduduk',
        'with' => ['kk'],
        'fields' => [
            ['name' => 'kk_id', 'label' => 'Nomor KK', 'type' => 'select', 'relation' => 'App\Models\Kk', 'display' => 'nomor_kk'],
            ['name' => 'nik', 'label' => 'NIK', 'type' => 'text'],
            ['name' => 'nama_lengkap', 'label' => 'Nama Lengkap', 'type' => 'text'],
            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'enum', 'options' => ['Laki-laki', 'Perempuan']],
            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date'],
            ['name' => 'pekerjaan', 'label' => 'Pekerjaan', 'type' => 'text']
        ]
    ],
    'Nelayan' => [
        'route' => 'nelayans',
        'var' => 'nelayan',
        'with' => ['penduduk'],
        'fields' => [
            ['name' => 'penduduk_id', 'label' => 'Penduduk (NIK - Nama)', 'type' => 'select', 'relation' => 'App\Models\Penduduk', 'display' => 'nama_lengkap'],
            ['name' => 'jenis_kapal', 'label' => 'Jenis Kapal', 'type' => 'text'],
            ['name' => 'alat_tangkap', 'label' => 'Alat Tangkap', 'type' => 'text']
        ]
    ],
    'Petani' => [
        'route' => 'petanis',
        'var' => 'petani',
        'with' => ['penduduk'],
        'fields' => [
            ['name' => 'penduduk_id', 'label' => 'Penduduk', 'type' => 'select', 'relation' => 'App\Models\Penduduk', 'display' => 'nama_lengkap'],
            ['name' => 'luas_lahan_m2', 'label' => 'Luas Lahan (m2)', 'type' => 'number'],
            ['name' => 'jenis_komoditas', 'label' => 'Jenis Komoditas', 'type' => 'text']
        ]
    ],
    'Umkm' => [
        'route' => 'umkms',
        'var' => 'umkm',
        'with' => ['penduduk'],
        'fields' => [
            ['name' => 'penduduk_id', 'label' => 'Penduduk', 'type' => 'select', 'relation' => 'App\Models\Penduduk', 'display' => 'nama_lengkap'],
            ['name' => 'nama_usaha', 'label' => 'Nama Usaha', 'type' => 'text'],
            ['name' => 'bidang_usaha', 'label' => 'Bidang Usaha', 'type' => 'text']
        ]
    ],
    'RumahTanggaSasaran' => [
        'route' => 'rts',
        'var' => 'rumahTanggaSasaran',
        'with' => ['kk'],
        'fields' => [
            ['name' => 'kk_id', 'label' => 'Nomor KK', 'type' => 'select', 'relation' => 'App\Models\Kk', 'display' => 'nomor_kk'],
            ['name' => 'kriteria_bantuan', 'label' => 'Kriteria Bantuan', 'type' => 'text'],
            ['name' => 'status_penerima', 'label' => 'Status Penerima', 'type' => 'enum', 'options' => ['Layak', 'Tidak Layak', 'Menerima']]
        ]
    ]
];

$controllerStub = <<<EOT
<?php

namespace App\Http\Controllers\Disperindag;

use App\Http\Controllers\Controller;
use App\Models\{Model};
use Illuminate\Http\Request;
{Imports}

class {Model}Controller extends Controller
{
    public function index()
    {
        \$items = {Model}::{With}latest()->paginate(10);
        return view('disperindag.{Route}.index', compact('items'));
    }

    public function create()
    {
        {CreateData}
        return view('disperindag.{Route}.create'{Compact});
    }

    public function store(Request \$request)
    {
        \$request->validate([
            {ValidationRules}
        ]);

        {Model}::create(\$request->all());
        return redirect()->route('disperindag.{Route}.index')->with('success', '{Model} berhasil ditambahkan.');
    }

    public function edit({Model} \${Var})
    {
        {CreateData}
        \$item = \${Var};
        return view('disperindag.{Route}.edit', compact('item'{CompactStr}));
    }

    public function update(Request \$request, {Model} \${Var})
    {
        \$request->validate([
            {ValidationRulesUpdate}
        ]);

        \${Var}->update(\$request->all());
        return redirect()->route('disperindag.{Route}.index')->with('success', '{Model} berhasil diperbarui.');
    }

    public function destroy({Model} \${Var})
    {
        \${Var}->delete();
        return redirect()->route('disperindag.{Route}.index')->with('success', '{Model} berhasil dihapus.');
    }
}
EOT;

$indexStub = <<<EOT
@extends('layouts.app')
@section('title', 'Data {Model}')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-900">Kelola Data {Model}</h2>
    <a href="{{ route('disperindag.{Route}.create') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl shadow-md transition-all duration-300 font-bold">Tambah {Model}</a>
</div>

<div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-slate-900 border-b border-gray-200">
                {TableHeaders}
                <th class="p-4 font-semibold text-sm">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse(\$items as \$item)
            <tr class="hover:bg-gray-50 transition-all duration-300">
                {TableColumns}
                <td class="p-4 flex space-x-2">
                    <a href="{{ route('disperindag.{Route}.edit', \$item->id) }}" class="bg-emerald-700 hover:bg-emerald-800 text-white px-3 py-1.5 rounded-xl shadow-sm transition-all duration-300 text-sm font-semibold">Edit</a>
                    <form action="{{ route('disperindag.{Route}.destroy', \$item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus data ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-xl shadow-sm transition-all duration-300 text-sm font-semibold">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="100%" class="p-4 text-center text-slate-500">Belum ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">
    {{ \$items->links() }}
</div>
@endsection
EOT;

$createStub = <<<EOT
@extends('layouts.app')
@section('title', 'Tambah {Model}')
@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-slate-900 mb-6">Tambah Data {Model}</h2>
    
    <form action="{{ route('disperindag.{Route}.store') }}" method="POST" class="space-y-6">
        @csrf
        {FormFields}
        <div class="flex justify-end space-x-3">
            <a href="{{ route('disperindag.{Route}.index') }}" class="bg-gray-200 hover:bg-gray-300 text-slate-900 px-4 py-2 rounded-xl shadow-sm transition-all duration-300 font-bold">Batal</a>
            <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl shadow-md transition-all duration-300 font-bold transform hover:scale-105">Simpan</button>
        </div>
    </form>
</div>
@endsection
EOT;

$editStub = <<<EOT
@extends('layouts.app')
@section('title', 'Edit {Model}')
@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-slate-900 mb-6">Edit Data {Model}</h2>
    
    <form action="{{ route('disperindag.{Route}.update', \$item->id) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        {FormFieldsEdit}
        <div class="flex justify-end space-x-3">
            <a href="{{ route('disperindag.{Route}.index') }}" class="bg-gray-200 hover:bg-gray-300 text-slate-900 px-4 py-2 rounded-xl shadow-sm transition-all duration-300 font-bold">Batal</a>
            <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl shadow-md transition-all duration-300 font-bold transform hover:scale-105">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
EOT;

@mkdir('app/Http/Controllers/Disperindag', 0755, true);

foreach($entities as $model => $config) {
    $route = $config['route'];
    $var = $config['var'];
    @mkdir("resources/views/disperindag/{$route}", 0755, true);

    // Build Controller Parts
    $imports = "";
    $createData = "";
    $compactStr = "";
    $compactArray = [];
    $validationRules = [];
    $validationRulesUpdate = [];

    foreach($config['fields'] as $field) {
        $validationRules[] = "'{$field['name']}' => 'required'";
        $validationRulesUpdate[] = "'{$field['name']}' => 'required'";
        
        if ($field['type'] == 'select') {
            $relModelPath = explode('\\', $field['relation']);
            $relModel = end($relModelPath);
            $imports .= "use {$field['relation']};\n";
            $relVar = strtolower($relModel) . 's';
            $createData .= "\${$relVar} = {$relModel}::all();\n        ";
            $compactArray[] = "'{$relVar}'";
        }
    }

    if(count($compactArray) > 0) {
        $compactStr = ", " . implode(', ', $compactArray);
        $compact = ", compact(" . implode(', ', $compactArray) . ")";
    } else {
        $compact = "";
        $compactStr = "";
    }

    $withStr = isset($config['with']) ? "with(['" . implode("','", $config['with']) . "'])->" : "";

    $controllerContent = str_replace(
        ['{Model}', '{Route}', '{Var}', '{Imports}', '{CreateData}', '{Compact}', '{CompactStr}', '{ValidationRules}', '{ValidationRulesUpdate}', '{With}'],
        [$model, $route, $var, $imports, $createData, $compact, $compactStr, implode(",\n            ", $validationRules), implode(",\n            ", $validationRulesUpdate), $withStr],
        $controllerStub
    );
    file_put_contents("app/Http/Controllers/Disperindag/{$model}Controller.php", $controllerContent);

    // Build Index View
    $tableHeaders = "";
    $tableColumns = "";
    foreach($config['fields'] as $field) {
        $tableHeaders .= "<th class=\"p-4 font-semibold text-sm\">{$field['label']}</th>\n                ";
        if ($field['type'] == 'select') {
            $relName = str_replace('_id', '', $field['name']);
            $tableColumns .= "<td class=\"p-4 text-sm text-slate-700\">{{ \$item->{$relName}->{$field['display']} ?? '-' }}</td>\n                ";
        } else {
            $tableColumns .= "<td class=\"p-4 text-sm text-slate-700\">{{ \$item->{$field['name']} }}</td>\n                ";
        }
    }
    
    $indexContent = str_replace(
        ['{Model}', '{Route}', '{TableHeaders}', '{TableColumns}'],
        [$model, $route, $tableHeaders, $tableColumns],
        $indexStub
    );
    file_put_contents("resources/views/disperindag/{$route}/index.blade.php", $indexContent);

    // Build Create/Edit Views
    $formFields = "";
    $formFieldsEdit = "";
    foreach($config['fields'] as $field) {
        $formFields .= "<div>\n            <label class=\"block text-sm font-medium text-slate-900 mb-1\">{$field['label']}</label>\n";
        $formFieldsEdit .= "<div>\n            <label class=\"block text-sm font-medium text-slate-900 mb-1\">{$field['label']}</label>\n";
        
        if ($field['type'] == 'text' || $field['type'] == 'number' || $field['type'] == 'date') {
            $inputType = $field['type'] == 'text' ? 'text' : ($field['type'] == 'number' ? 'number' : 'date');
            $formFields .= "            <input type=\"{$inputType}\" name=\"{$field['name']}\" required class=\"w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300\">\n";
            $formFieldsEdit .= "            <input type=\"{$inputType}\" name=\"{$field['name']}\" value=\"{{ \$item->{$field['name']} }}\" required class=\"w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300\">\n";
        } elseif ($field['type'] == 'textarea') {
            $formFields .= "            <textarea name=\"{$field['name']}\" required rows=\"3\" class=\"w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300\"></textarea>\n";
            $formFieldsEdit .= "            <textarea name=\"{$field['name']}\" required rows=\"3\" class=\"w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300\">{{ \$item->{$field['name']} }}</textarea>\n";
        } elseif ($field['type'] == 'enum') {
            $formFields .= "            <select name=\"{$field['name']}\" required class=\"w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300\">\n";
            $formFieldsEdit .= "            <select name=\"{$field['name']}\" required class=\"w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300\">\n";
            foreach($field['options'] as $opt) {
                $formFields .= "                <option value=\"{$opt}\">{$opt}</option>\n";
                $formFieldsEdit .= "                <option value=\"{$opt}\" {{ \$item->{$field['name']} == '{$opt}' ? 'selected' : '' }}>{$opt}</option>\n";
            }
            $formFields .= "            </select>\n";
            $formFieldsEdit .= "            </select>\n";
        } elseif ($field['type'] == 'select') {
            $relModelPath = explode('\\', $field['relation']);
            $relModelName = end($relModelPath);
            $relVar = strtolower($relModelName) . 's';
            $formFields .= "            <select name=\"{$field['name']}\" required class=\"w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300\">\n";
            $formFields .= "                <option value=\"\">-- Pilih {$field['label']} --</option>\n";
            $formFields .= "                @foreach(\${$relVar} as \$opt)\n";
            $formFields .= "                    <option value=\"{{ \$opt->id }}\">{{ \$opt->{$field['display']} }}</option>\n";
            $formFields .= "                @endforeach\n            </select>\n";

            $formFieldsEdit .= "            <select name=\"{$field['name']}\" required class=\"w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300\">\n";
            $formFieldsEdit .= "                <option value=\"\">-- Pilih {$field['label']} --</option>\n";
            $formFieldsEdit .= "                @foreach(\${$relVar} as \$opt)\n";
            $formFieldsEdit .= "                    <option value=\"{{ \$opt->id }}\" {{ \$item->{$field['name']} == \$opt->id ? 'selected' : '' }}>{{ \$opt->{$field['display']} }}</option>\n";
            $formFieldsEdit .= "                @endforeach\n            </select>\n";
        }
        $formFields .= "        </div>\n        ";
        $formFieldsEdit .= "        </div>\n        ";
    }

    $createContent = str_replace(
        ['{Model}', '{Route}', '{FormFields}'],
        [$model, $route, $formFields],
        $createStub
    );
    file_put_contents("resources/views/disperindag/{$route}/create.blade.php", $createContent);

    $editContent = str_replace(
        ['{Model}', '{Route}', '{FormFieldsEdit}'],
        [$model, $route, $formFieldsEdit],
        $editStub
    );
    file_put_contents("resources/views/disperindag/{$route}/edit.blade.php", $editContent);
}

echo "Scaffolding complete.\n";
