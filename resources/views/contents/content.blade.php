<!-- Contenido Principal -->
@extends('main')
@section('content')
    <template v-if="menu == 0">
        <category></category>
    </template>
    
    <template v-if="menu == 1">
        <articles></articles>
    </template>
@endsection
<!-- /Fin del contenido principal -->