@extends('layouts.dashboard')
@section('content')
    <main class="container mt-5">
        @if(isset($message))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{--                    <strong>Holy guacamole!</strong>--}}
                {{ $message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row justify-content-center">
            <aside class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ $title ?? 'Selecione uma planilha para começar' }}
                    </div>
                    <div class="card-body">
                        <p>
                            <b>Arquivo selecionado: </b>{{ $filename }}
                            <br>
                            <b>Pasta selecionada: </b>{{ $directory }}
                        </p>
                        <form action="{{ route('send') }}" method="POST">
                            @csrf
                            @method('POST')
                            <input type="hidden" value="{{ $filepath }}" name="filepath"/>
                            <input type="hidden" name="filename" value="{{ $filename }}">
                            <input type="hidden" name="directory" value="{{ $directory }}">
                            <div class="mt-3">
                                <div class="float-left">
                                    <a href="{{ route('index') }}" class="btn btn-secondary">Voltar ao inicio</a>
                                </div>
                                <div class="float-right">
                                    <button class="btn btn-primary">
                                        Confirmar <i class="fa fa-check float-right mt-1 ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </main>
@endsection
@section('js')
    <script>
        // file.addEventListener('change', () => {
        //     let fullPath = document.getElementById('file').value;
        //     if (fullPath) {
        //         let startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
        //         let filename = fullPath.substring(startIndex);
        //         if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
        //             filename = filename.substring(1);
        //         }
        //         fileName = (file.getAttribute('data-multiple-caption') || '').replace('{name}', filename);
        //         files_label.innerHTML = fileName;
        //     }
        // });
        excel.addEventListener('change', () => {
            let fullPath = document.getElementById('excel').value;
            if (fullPath) {
                let startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                let filename = fullPath.substring(startIndex);
                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                    filename = filename.substring(1);
                }
                fileName = (excel.getAttribute('data-multiple-caption') || '').replace('{name}', filename);
                excel_label.innerHTML = fileName;
            }
        });
    </script>
@endsection
