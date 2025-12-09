<!DOCTYPE html>
<html>
<head>
    <title>Manual Material Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        input:focus { outline: none; border-color: #007bff; }
        button { background: #007bff; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #0056b3; }
        .error { color: #dc3545; font-size: 12px; margin-top: 5px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .debug { background: #f8f9fa; border: 1px solid #ddd; padding: 15px; margin-top: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Manual Material Form Test</h1>
        
        @if(session('success'))
        <div class="success">
            ✅ {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="error" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            ❌ {{ session('error') }}
        </div>
        @endif
        
        @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3>Validation Errors:</h3>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <div class="info">
            <strong>Test Form ke Route: {{ route('material.store') }}</strong><br>
            CSRF Token: <code>{{ csrf_token() }}</code>
        </div>
        
        <form action="{{ route('material.store') }}" method="POST" id="testForm">
            @csrf
            
            <div class="form-group">
                <label for="kode_material">Kode Material *</label>
                <input type="text" id="kode_material" name="kode_material" 
                       value="MAT-TEST-{{ date('Ymd-His') }}" required>
                @error('kode_material')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="nama_material">Nama Material *</label>
                <input type="text" id="nama_material" name="nama_material" 
                       value="Material Test Manual" required>
                @error('nama_material')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="satuan">Satuan *</label>
                <input type="text" id="satuan" name="satuan" value="PCS" required>
                @error('satuan')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="stok_awal">Stok Awal *</label>
                <input type="number" id="stok_awal" name="stok_awal" value="100" min="0" required>
                @error('stok_awal')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="min_stok">Stok Minimum *</label>
                <input type="number" id="min_stok" name="min_stok" value="10" min="0" required>
                @error('min_stok')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit">🚀 Test Submit Material</button>
        </form>
        
        <div class="debug">
            <h3>Debug Info:</h3>
            <p><strong>Current Route:</strong> {{ Route::currentRouteName() }}</p>
            <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
            <p><strong>Has Old Input:</strong> {{ count(old()) > 0 ? 'Yes' : 'No' }}</p>
            
            @if(session('debug_info'))
            <h4>Debug from Controller:</h4>
            <pre>{{ json_encode(session('debug_info'), JSON_PRETTY_PRINT) }}</pre>
            @endif
        </div>
        
        <div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px;">
            <h3>Quick Test Links:</h3>
            <p><a href="{{ route('debug.material.create-test') }}" target="_blank">1. Test Create Form</a></p>
            <p><a href="/debug/material/test-save" target="_blank">2. Test Save Directly (API)</a></p>
            <p><a href="/debug/material/test-validation" target="_blank">3. Test Validation Rules</a></p>
            <p><a href="/debug/material/database-check" target="_blank">4. Check Database</a></p>
            <p><a href="{{ route('material.index') }}">5. Back to Material List</a></p>
        </div>
    </div>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            console.log('Form submitted with data:', {
                kode_material: document.getElementById('kode_material').value,
                nama_material: document.getElementById('nama_material').value,
                satuan: document.getElementById('satuan').value,
                stok_awal: document.getElementById('stok_awal').value,
                min_stok: document.getElementById('min_stok').value,
                _token: document.querySelector('input[name="_token"]').value
            });
        });
    </script>
</body>
</html>