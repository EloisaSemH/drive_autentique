@extends('layouts.dashboard')
@section('css')
    <style>
        .inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .inputfile + label {
            padding: 0 10px;
            font-size: 1rem;
            line-height: 28px;
            border: 1px solid transparent;
            border-color: #d5d8de;
            border-radius: 2px;
            display: inline-block;
            cursor: pointer;
        }

        .inputfile + label i {
            margin-right: 10px;
        }
    </style>
@endsection
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
                        </p>
                        <hr class="my-3">
                        @if(isset($dirsInDir))
                            <h4>
                                Diretórios
                            </h4>
                            <form action="{{ route('review') }}" method="POST">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="filename" value="{{ $filename }}">
                                <ul class="list-group">
                                    @foreach($dirsInDir as $dir)
                                        <li class="list-group-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="directory"
                                                       id="{{ $dir['basename'] }}" value="{{ $dir['name'] }}" required>
                                                <label class="form-check-label" for="{{ $dir['basename'] }}">
                                                    {{ $dir['name'] }}
                                                </label>
                                                <a href="#">
                                                    <i class="fa fa-arrow-right float-right mt-1"></i>
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="text-right">
                                    <button class="btn btn-primary mt-3">
                                        Avançar <i class="fa fa-arrow-right float-right mt-1 ml-2"></i>
                                    </button>
                                </div>
                            </form>
                        @else
                            <h4>
                                Nenhum diretório nessa pasta!
                            </h4>
                        @endif
                        @if(isset($filesInDir))
                            <hr class="my-3">
                            <h4>
                                Todos os arquivos da pasta atual
                            </h4>
                            <ul class="list-group">
                                @foreach($filesInDir as $file)
                                    <li class="list-group-item">
                                        {{ $file['name'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <h4>
                                Nenhum arquivo nessa pasta!
                            </h4>
                        @endif
                        <div>
                            @if(isset($goBack))
                                <a href="{{ $goBack }}" class="btn btn-secondary">Voltar</a>
                            @endif
                        </div>
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
