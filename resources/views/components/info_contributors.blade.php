<div class='d-flex flex-column justify-content-center align-items-center mx-4 mb-3'>
    <img class="clip-path regular-image" src="{{ $image ?? 'img/user2.png' }}" alt="{{ $alt_image ?? 'foto-dev' }}">
    <div class="text-center">
        <h3>{{$name ?? ''}}</h3>
        <p class='p-text'>{{$profession ?? ''}} <code> {{$occupation ?? ''}}</code></p>   
        <p class='p-text'>
            @if (isset($email))
                <a href="mailto:{{ $email }}" class="smaller-p" target='_blank' > Email </a>     
            @endif
            @if (isset($email) && isset($lattes))
                |
            @endif
            @if (isset($lattes))
                <a href="{{ $lattes }}" class="smaller-p" target='_blank'> Lattes </a>       
            @endif
        </p>
    </div>
</div>