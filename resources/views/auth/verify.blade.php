@extends('layouts.main')

@section('content')
<div class="container">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card w-100" style="max-width: 500px;">
            <div class="card-header">
                <h5>Verifica tu correo</h5>
            </div>
            <div class="card-body">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('Se ha enviado un enlace de verificación a su cuenta de correo') }}
                    </div>
                @endif

                {{ __('Revise su cuenta de correo electrónico antes de continuar.') }}
                {{ __('Si no ha recibido el enlace') }},
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('haga click aquí para volver a enviarlo') }}</button>.
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
