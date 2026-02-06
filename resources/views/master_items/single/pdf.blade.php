<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Item - {{ $data->kode }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table tr th {
            text-align: left;
            padding: 8px;
            width: 30%;
            vertical-align: top;
            font-weight: bold;
        }
        table tr td {
            padding: 8px;
            vertical-align: top;
        }
        table tr td:first-child {
            width: 5%;
            text-align: center;
        }
        table tr {
            border-bottom: 1px solid #ddd;
        }
        ul {
            margin: 0;
            padding-left: 20px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 10px;
            font-size: 10px;
            color: #666;
            background-color: white;
        }
        .content {
            margin-bottom: 80px; /* Space for footer */
        }
        img {
            max-width: 200px;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: #007bff;
            color: white;
            border-radius: 3px;
            font-size: 11px;
        }
        .price {
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>DETAIL ITEM BARANG</h2>
        <p>Kode: <strong>{{ $data->kode }}</strong></p>
    </div>

    <div class="content">
        <table>
            <tr>
                <th>Nama</th>
                <td>:</td>
                <td>{{ $data->nama }}</td>
            </tr>
            <tr>
                <th>Harga Beli</th>
                <td>:</td>
                <td>Rp {{ number_format($data->harga_beli, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Laba</th>
                <td>:</td>
                <td>{{ $data->laba }}%</td>
            </tr>
            <tr>
                <th>Harga Jual</th>
                <td>:</td>
                <td class="price">
                    Rp {{ number_format($data->harga_beli + $data->harga_beli * $data->laba / 100, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <th>Supplier</th>
                <td>:</td>
                <td>{{ $data->supplier }}</td>
            </tr>
            <tr>
                <th>Jenis</th>
                <td>:</td>
                <td><span class="badge">{{ $data->jenis }}</span></td>
            </tr>
            @if(!empty($data->foto))
            <tr>
                <th>Foto</th>
                <td>:</td>
                <td>
                    <img src="{{ public_path('storage/'.$data->foto) }}" alt="Foto Item">
                </td>
            </tr>
            @endif
            @if($categories->isNotEmpty())
            <tr>
                <th>Kategori</th>
                <td>:</td>
                <td>
                <table style="width: 100%; border: 1px solid #ddd; margin: 0;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="padding: 5px; border: 1px solid #ddd; width: 30%;">Kode</th>
                    <th style="padding: 5px; border: 1px solid #ddd; width: 70%;">Nama Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td style="padding: 5px; border: 1px solid #ddd;">{{ $cat->category_kode }}</td>
                    <td style="padding: 5px; border: 1px solid #ddd;">{{ $cat->category_nama }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
                </td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ $timestamp }}</p>
        <p>Sistem Informasi Inventory Management</p>
    </div>
</body>
</html>