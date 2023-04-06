<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}" />
    </head>
    <body>
        <div id="app">
            <subscribers-list/>
        </div>
        <script type="module" src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
