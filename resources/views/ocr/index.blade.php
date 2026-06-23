<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan KTP Pegawai Baru (OCR)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session('success'))
                <div class="alert alert-success shadow-sm mb-4">✨ {{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">📷 Scan KTP Pegawai Baru (OCR)</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ocr.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="form-label fw-semibold">Unggah Foto KTP</label>
                            <input type="file" name="image" class="form-control form-control-lg" accept="image/*" required>
                            <div class="form-text text-muted mt-2">Format berkas: JPG, JPEG, atau PNG.</div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">Mulai Ekstraksi OCR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>