@extends('layouts.template')
@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/level/import') }}')" class="btn btn-info">Import Level</button>
                <a href="{{ url('/level/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Level User</a>
                <a href="{{ url('/level/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Level User</a>
                <button onclick="modalAction('{{ url('/level/create_ajax') }}')" class="btn btn-success">Tambah Ajax</button>

            </div>
        </div>
        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_level" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_level" class="form-control form-control-sm filter_level">
                                    <option value="">- Semua -</option>
                                    {{-- @foreach($levels as $level)
                                        <option value="{{ $level->level_id }}">{{ $level->level_nama }}</option>
                                    @endforeach --}}
                                </select>
                                <small class="form-text text-muted">Filter Level</small>
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
            {{-- TIDAK PAKAI FILTER
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label" for="level_id">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="level_id" name="level_id" required>
                                <option value="">- Semua -</option>
                                @foreach($level as $item)
                                    <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Level Pengguna</small>
                        </div>
                    </div>
                </div>
            </div> --}}
            <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Level</th>
                        <th>Nama Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataLevel;
        $(document).ready(function() {
            var dataLevel = $('#table_level').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('level/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d){
                        d.level_id = $('#level_id').val();
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "level_kode",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "level_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        // width: "14%",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#table-level_filter input').unbind().bind().on('keyup', function(e) {
            if (e.keyCode == 13) { // enter key
                tableLevel.search(this.value).draw();
            }
        });
            // $('#level_id').on('change', function(){
            //     dataLevel.ajax.reload();
            // });
            $('.filter_level').change(function() {
            tableLevel.draw();
            });
        });
    </script>
@endpush