@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('master-items')}}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">
                <div class="card-header">Master Item</div>

                <div class="card-body">
                    <table>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{$data->nama}}</td>
                        </tr>
                        <tr>
                            <th>Harga Beli</th>
                            <td>:</td>
                            <td>{{$data->harga_beli}}</td>
                        </tr>
                        <tr>
                            <th>Laba</th>
                            <td>:</td>
                            <td>{{$data->laba}}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>:</td>
                            <td>{{$data->harga_beli + $data->harga_beli * $data->laba / 100 }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>:</td>
                            <td>{{$data->supplier}}</td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td>:</td>
                            <td>{{$data->jenis}}</td>
                        </tr>

                        @if(!empty($data->foto))
                            <tr>
                                <th>Foto</th>
                                <td>:</td>
                                <td>
                                    <img src="{{ asset('storage/'.$data->foto) }}" alt="Foto Item" style="max-width:200px;">
                                </td>
                            </tr>
                            @endif


                                @if($categories->isNotEmpty())
                                <tr>
                                    <th>Kategori</th>
                                    <td>:</td>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach($categories as $cat)
                                                <li>{{ $cat->category_kode }} - {{ $cat->category_nama }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                @endif


                    </table>
                    <a class="btn btn-info" href="{{url('master-items/form/edit')}}/{{$data->id}}">Edit</a>
                    <a class="btn btn-danger" href="{{url('master-items/delete')}}/{{$data->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    <a href="{{ route('master-items.pdf', $data->kode) }}"
           class="btn btn-danger"
           target="_blank">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection