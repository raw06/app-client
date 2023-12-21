<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Laravel') }}</title>


  @if(app()->environment('local'))
    <script type="module">
      import RefreshRuntime from 'http://localhost:5173/@react-refresh'
      RefreshRuntime.injectIntoGlobalHook(window)
      window.$RefreshReg$ = () => {}
      window.$RefreshSig$ = () => (type) => type
      window.__vite_plugin_react_preamble_installed__ = true
    </script>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
  @else
    <!-- Styles -->
    <link rel="stylesheet" href="{{ config('shopify.cdn_base_script_tag') }}/dist/{{ $cssFileName }}" />
  @endif
</head>

<body>
<div id="root"></div>
@if(app()->environment('local'))
  <script type="module" src="http://localhost:5173/resources/js/index.jsx"></script>
@else
  <script type="module" src="{{ config('shopify.cdn_base_script_tag') }}/dist/{{ $jsFileName }}"></script>
@endif
</body>

</html>
