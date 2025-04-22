@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stok Barang</h3>
            <div class="card-tools">
                <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Kategori Barang</a>
                <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Kategori Barang</a>
                <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info">Import Data Kategori</button>
                <button onclick="modalAction('{{ url('stok/create_ajax')}}')" class="btn btn-success mt-1">Tambah Ajax</button>
            </div>
        </div>
        <div class="card-body">
            <!-- untuk Filter data -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label class="col-1 control-label col-form-label">Filter:</label>
                            <div class="col-3">
                                <select class="form-control" id="supplier_id" name="supplier_id" required>
                                    <option value="">- Semua -</option>
                                    @foreach ($supplier as $item)
                                        <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Supplier</small>
                            </div>
                            <div class="col-3">
                                <select class="form-control" id="barang_id" name="barang_id" required>
                                    <option value="">- Semua -</option>
                                    @foreach ($barang as $item)
                                        <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Barang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-sm table-striped table-hover" id="table-stok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Staff</th>
                        <th>Nama Supplier</th>
                        <th>Nama Barang</th>
                        <th>Stok Tanggal</th>
                        <th>Stok Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="75%"></div>
@endsection
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var tableStok;

        $(document).ready(function() {
            tableStok = $('#table-stok').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('stok/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.supplier_id = $('#supplier_id').val();
                        d.barang_id = $('#barang_id').val();
                    }
                },

                columns: [
                {
                // nomor urut dari laravel datatable addIndexColumn() data: "DT_RowIndex",
                data: "DT_RowIndex",
                className: "text-center",
                orderable: false,
                searchable: false
                },
                {
                    data: "user.nama",
                    className: "text-center",
                    orderable: true,
                    searchable: true,
                },
                {
                    data: "supplier.supplier_nama",
                    className: "text-center",
                    orderable: true,
                    searchable: true
                }, {
                    data: "barang.barang_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "stok_tanggal",
                    className: "text-center",
                    orderable: true,
                    searchable: false,
                }, {
                    data: "stok_jumlah",
                    className: "text-center",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }]
            });
            $('#table-stok_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // enter key
                    tableStok.search(this.value).draw();
                }
            });
            $('#supplier_id').on('change', function() {
                tableStok.ajax.reload();
            });
            $('#barang_id').on('change', function() {
                tableStok.ajax.reload();
            });
        });
    </script>
    @endpush