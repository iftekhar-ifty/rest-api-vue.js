<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{asset('website/css/bootstrap.min.css')}}" rel="stylesheet">

    </head>
    <body>
<section id="app"> 
    <div class="container">
          <main-component></main-component>
    </div>
</section>







  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="{{asset('website/js/bootstrap.bundle.min.js')}}"></script>
    </body>
</html>
