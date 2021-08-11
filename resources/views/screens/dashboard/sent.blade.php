@extends('layouts.dashboard')
@section('content')
    <main class="container mt-5">
        <div class="row justify-content-center">
            <aside class="col-md-6">
                <div class="card {{ 'bg-'.$bgColor ?? '' }} text-white">
                    <div class="card-header">
                        {{ $title ?? 'Sucesso!' }}
                    </div>
                    <div class="card-body">
                        <p>
                            {{ $message ?? '' }}
                        </p>
                        <div class="text-center">
                            <a href="{{ route('index') }}" class="btn btn-outline-light mt-2">
                                Voltar ao inicio
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>
@endsection
