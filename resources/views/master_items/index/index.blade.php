@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('master-items/form/new')}}" class="btn btn-secondary">+ Master Items Baru</a>
                <a href="{{ route('category-items.index') }}" id="btn-export-excel" class="btn btn-secondary">
                    Kategori master
                </a>
                <a href="#" id="btn-export-excel" class="btn btn-success">
    Export Excel
</a>

            </div>
            <div class="card">
                <div class="card-header">Daftar Master Itemss</div>

                <div class="card-body">
                    @include('master_items.index.filter')
                    @include('master_items.index.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@include('master_items.index.js')
<script>
document.getElementById('btn-export-excel').addEventListener('click', function (e) {
    const params = new URLSearchParams({
        kode: document.getElementById('filter-kode').value,
        nama: document.getElementById('filter-nama').value,
        harga_min: document.getElementById('filter-harga-min').value,
        harga_max: document.getElementById('filter-harga-max').value,
    });

    window.location.href =
        "{{ route('master-items.export.excel') }}?" + params.toString();
});
</script>
@endsection

