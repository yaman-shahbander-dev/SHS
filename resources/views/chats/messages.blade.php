<?php
    
?>

<section class="msger">
  
@can('messages.index')
  <main class="msger-chat">
      @if(count($messages) != 0)
        @foreach($messages as $message)
            @if($message->is_customer == 1)
            
                <div class="msg left-msg">
                  <div
                   class="msg-img"
                   style="background-image: url({{url('/images/user.svg')}})"
                  ></div>
            
                  <div class="msg-bubble">
                    <div class="msg-info">
                        
                      <div class="msg-info-name">{{$message->user->name}}</div>
                      <div class="msg-info-time">{{$message->created_at->diffForHumans()}}</div>
                    </div>
            
                    <div class="msg-text">
                      {{$message->msg}}
                    </div>
                  </div>
                </div>
            @else
            
                <div class="msg right-msg">
                  <div
                   class="msg-img"
                   style="background-image: url({{url('/images/kitchen.svg')}})"
                  ></div>
            
                  <div class="msg-bubble">
                    <div class="msg-info">
                      <div class="msg-info-name">{{$message->user->name}}</div>
                      <div class="msg-info-time">{{$message->created_at->diffForHumans()}}</div>
                    </div>
            
                    <div class="msg-text">
                      {{$message->msg}}
                    </div>
                  </div>
                </div>
            @endif
    
    
        @endforeach
        
        @else
        
            <p style='padding:20px;font-size:1.2rem'>No Messages For This Chat ...! </p>
        
        @endif
      
    
    

    
  </main>
@endcan

@cannot('messages.index')
    <main class="msger-chat">
        
        you don't have permission to see messages for this chat ...!
    </main>
@endcan
 
</section>