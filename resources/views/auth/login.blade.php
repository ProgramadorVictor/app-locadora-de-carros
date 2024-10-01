@extends('layouts.app')

@section('content')
    {{-- @csrf Input completo, com display: hidden; --}}
    {{-- {{@csrf_token()}} Somente traz o valor do csrf_token --}}
    {{-- No vue.js não conseguimos receber props com CamelCase. Se você passar um prop 'ABCde', no contexto do vue estará 'abcde' --}}
    <login-component csrf_token={{@csrf_token()}} nome="Victor" uid="1032" ABCde="Padrão CamelCase Não Funciona" kebab-case="kebab-case Pode virar CamelCase"/>{{-- Componente vue sendo registrado em resources/app.js e sendo renderizado aqui. TO ANIMADAO KKKKKKKK --}}
    {{-- Passando o token do Blade para o componente vue.js --}}
@endsection
