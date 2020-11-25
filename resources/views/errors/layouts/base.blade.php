@extends('app')

<title>@yield('title')</title>

@include('nav')

@section('content')
<div class="error-wrap">
  <section>
    <h1 class="text-center mt-3 mb-3">@yield('title')</h1>
    <p class="error-message text-center h4 font-weight-light">@yield('message')</p>
    <p class="error-detail text-center h5 font-weight-light mb-2">@yield('detail')</p>
    @yield('link')
  </section>
</div>