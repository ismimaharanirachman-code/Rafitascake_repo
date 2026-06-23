<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Data Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-secondary text-white fw-semibold">Foto KTP</div>
                <div class="card-body text-center p-2 bg-dark">
                    <img src="{{ asset($imagePath) }}" class="img-fluid rounded" alt="Foto KTP" style="max-height: 280px;">
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-semibold">Raw Text (Hasil Mentah OCR)</div>
                <div class="card-body bg-black text-success p-3 rounded-bottom" style="font-family: monospace; font-size: 13px;">
                    NIK: {{ $extractedData['nik'] }}<br>
                    Nama: {{ $extractedData['nama'] }}<br>
                    Lahir: {{ $extractedData['tempat_lahir'] }}, {{ $extractedData['tanggal_lahir'] }}<br>
                    Alamat: {{ $extractedData['alamat'] }}
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <form action="{{ route('ocr.store-pegawai') }}" method="POST">
                @csrf
                <input type="hidden" name="image_path" value="{{ $imagePath }}">
                <input type="hidden" name="tempat_lahir" value="{{ $extractedData['tempat_lahir'] }}">
                <input type="hidden" name="tanggal_lahir" value="{{ $extractedData['tanggal_lahir'] }}">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-semibold">📋 Verifikasi Data Pegawai Baru</div>
                    <div class="card-body p-4">
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">NIK KTP</label>
                            <input type="text" name="nik" class="form-control" value="{{ $extractedData['nik'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nama Pegawai (Sesuai KTP)</label>
                            <input type="text" name="nama_pegawai" class="form-control" value="{{ $extractedData['nama'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Alamat Lengkap</label>
                            <textarea name="alamat_pegawai" class="form-control" rows="2" required>{{ $extractedData['alamat'] }}</textarea>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-primary fw-bold mb-3">Atribut Manajemen Kerja (Rafitascake)</h6>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Jabatan Kerja</label>
                            <select name="id_jabatan" class="form-select" required>
                                <option value="">-- Pilih Jabatan Pegawai --</option>
                                @foreach($listJabatan as $jabatan)
                                    <option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->nama_jabatan }} (Rp{{ number_format($jabatan->gaji_pokok, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Nomor HP Aktif</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 0812XXXXXXXX" required>
                        </div>

                        <div class="d-flex justify-content-between pt-2">
                            <a href="{{ route('ocr.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-success px-4 shadow-sm">Simpan ke Master Pegawai</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>