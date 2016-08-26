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
                <ul class="todo-controls">
                    <li>
                        <button type="button" class="button">Add</button>
                    </li>
                    <li>
                        <button type="button" class="button">Delete</button>
                    </li>                    
                </ul>
            
                <ul class="todo-list">
                    @foreach ($items as $item)                        
                        <li>  <!-- class="done" -->
                            <input type="checkbox" id="{{$item->id}}" /> <!-- checked disabled --> 
                            <label class="toggle" for="{{$item->id}}"></label>
                            {{$item->getContent()}}
                            @if($item === $items->last())
                                <br/>
                                @if (session('status'))        
                                    <span id="temp_message">
                                        {{ session('status') }}
                                    </span>
                                @endif  
                            @endif
                        </li>
                    @endforeach
                    
                    <li style="display:block;">
                        <form method="POST" action="/new">
                            <input type="text" name="text" id="add-text"></input>
                            <input type="submit" class="button" value="Save"></input> 
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>                        
                    </li>                                            
                </ul>                
            </section>
        </div>
    </body>
</html>
