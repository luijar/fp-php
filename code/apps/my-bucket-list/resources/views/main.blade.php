<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>My Bucket List | Home</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <div class="container">
            <section class="todo">

                @if (session('status'))        
                    <ul class="todo-controls">
                        <li>
                            {{ session('status') }}
                        </li>                                        
                    </ul>                    
                @endif                      

                <ul class="todo-list">
                    <form method="POST" action="/delete">
                        @foreach ($items as $item)                        
                            <li>  <!-- class="done" -->
                                <input type="checkbox" name="items[]" value="{{$item->id}}" id="item-{{$item->id}}" /> <!-- checked disabled --> 
                                <label class="toggle" for="item-{{$item->id}}"></label>
                                {{$item->getContent()}}
                            </li>
                        @endforeach
                        <li style="display:block;">
                            <input type="submit" class="button" value="Delete"></input>         
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </li>                                            
                    </form>
                    <li style="display:block;">
                        <form method="POST" action="/new">
                            <input type="text" name="text" id="add-text"></input>
                            <input type="submit" class="button" value="Add new"></input> 
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>                        
                    </li>                                            
                </ul>                
            </section>
        </div>
    </body>
</html>
