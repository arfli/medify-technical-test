@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Kategori Item</h4>
                    <a href="{{ route('category-items.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Search Form -->
                    <form action="{{ route('category-items.search') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="kode" class="form-control" placeholder="Cari Kode...">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="nama" class="form-control" placeholder="Cari Nama...">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ route('category-items.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Kode</th>
                                    <th width="40%">Nama</th>
                                    <th width="20%">Tanggal Dibuat</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                    <tr>
                                        <td>{{ $categories->firstItem() + $index }}</td>
                                        <td>{{ $category->kode }}</td>
                                        <td>{{ $category->nama }}</td>
                                        <td>{{ $category->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('category-items.show', $category->id) }}" 
                                               class="btn btn-sm btn-info" title="Lihat">
                                                    detail
                                            </a>
                                            <a href="{{ route('category-items.edit', $category->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                edit
                                            </a>
                                            <form action="{{ route('category-items.destroy', $category->id) }}" 
                                                  method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Yakin ingin menghapus?')" 
                                                        title="Hapus">
                                                    hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection