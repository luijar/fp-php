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
            <span style="font-size: 20px !important">My List</span>           
            <section class="todo">
                <ul class="todo-controls">
                    <li class="info">
                        @if(!$remaining_item_count)
                            You're all done!
                        @else
                            You have {{$remaining_item_count}} left!
                        @endif                        
                    </li>
                    @if (session('status'))        
                    <li id="info-panel">
                        {{ session('status') }}
                    </li>
                    @endif                                        
                </ul>                           
                                          
                <ul class="todo-list">            
                    @foreach ($items as $item)                                                
                        @if($item->state->getShortName() === 'completed' || $item->state->getShortName() === 'expired')
                            <li class="done">
                                <input type="checkbox" name="items[]" value="{{$item->id}}" id="item-{{$item->id}}" checked disabled/> 
                                <label class="toggle" for="item-{{$item->id}}"></label>
                                <span class="list-item-strike" id="content-{{$item->id}}">{{$item->getContent()}}</span>
                                <span id="delete-item-{{$item->id}}" style="display:inline; float:right;">
                                    <a href="/delete/{{$item->id}}">( delete )</a>
                                </span>
                            </li>
                        @else
                            <li>
                                <input type="checkbox" name="items[]" value="{{$item->id}}" id="item-{{$item->id}}" /> <!-- checked disabled --> 
                                <label class="toggle" for="item-{{$item->id}}"></label>
                                <span id="content-{{$item->id}}">{{$item->getContent()}}</span>
                                <span id="delete-item-{{$item->id}}" style="display:none; float:right;">
                                    <a href="/delete/{{$item->id}}">( delete )</a>
                                </span>
                            </li>
                        @endif                        
                    @endforeach                   
                    <li style="display:block;">
                        <form method="POST" action="/new">
                            Enter new item details: </br>
                            <input type="text" name="text" id="add-text" style="width: 75%" ></input>
                            <input type="submit" class="button" value="Add new"></input> 
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>                        
                    </li>                                            
                </ul>                
            </section>
        </div>

        <!-- Fucntional Libs -->
        <script src="https://npmcdn.com/@reactivex/rxjs@5.0.0-beta.11/dist/global/Rx.umd.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ramda/0.22.1/ramda.min.js"></script>
        
        <!-- Main app -->
        <script src="/js/app.js"></script>          
    </body>
</html>
