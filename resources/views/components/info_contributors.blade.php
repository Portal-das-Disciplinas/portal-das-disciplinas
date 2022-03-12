<div class='d-flex flex-column justify-content-center align-items-center mx-4 mb-3'>
    <img class="fluid clip-path" src="{{ $image ?? 'img/user2.png' }}" alt="{{ $alt_image ?? 'foto-dev' }}" style='width:30%;'>
    <div class="text-center">
        <h4>{{$name ?? ''}}</h4>
        <p>{{$profession ?? ''}} <code> {{$occupation ?? ''}}</code></p>   
        <p>
            @if (isset($email))
                <a href="mailto:{{ $email }}" class=""> Email </a>     
            @endif
            @if (isset($email) && isset($lattes))
                |
            @endif
            @if (isset($lattes))
                <a href="{{ $lattes }}" class=""> Lattes </a>       
            @endif
        </p>
    </div>
</div>