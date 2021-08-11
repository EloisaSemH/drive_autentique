<form class="col-4" method="POST" enctype="multipart/form-data"
      action="{{ $action ?? route('index.send') }}">
    @csrf
    @method($method ?? 'POST')
    {{--                <div class="form-group">--}}
    {{--                    <label for="email">Email</label>--}}
    {{--                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">--}}
    {{--                </div>--}}
    {{--                <div class="form-group">--}}
    {{--                    <label for="">Arquivo: *</label>--}}
    {{--                    <br>--}}
    {{--                    <input type="file" id="file" name="file"--}}
    {{--                           accept="application/pdf"--}}
    {{--                           data-multiple-caption="{name}"--}}
    {{--                           class="form-control @error('file') is-invalid @enderror inputfile" required>--}}
    {{--                    <label for="file" id="files_label">Selecionar arquivos</label>--}}
    {{--                    @error('file')--}}
    {{--                    <div class="invalid-feedback">{{ $message }}</div>--}}
    {{--                    @enderror--}}
    {{--                </div>--}}
    <div class="form-group">
        <label for="">Arquivo excel: *</label>
        <br>
        <input type="file" id="excel" name="excel"
               data-multiple-caption="{name}"
               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
               class="form-control @error('excel') is-invalid @enderror inputfile" required>
        <label for="excel" id="excel_label">Selecionar arquivos</label>
        @error('excel')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>
