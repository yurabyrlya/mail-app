<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <script src="{{ mix('/js/app.js') }}" defer></script>

        <div id="app">
            <subscribers-list/>
        </div>

    </body>
</html>
