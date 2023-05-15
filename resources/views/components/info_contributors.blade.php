<div class='d-flex flex-column  align-items-center mx-4 mb-3'>
    <img class="clip-path regular-image" src="{{($image=='' || null) ? 'img/profiles_img/user2.png' : '/storage/'.$image}}" alt="{{ $alt_image ?? 'foto-dev' }}">
    <div class="text-center">
        <h3>{{$name ?? ''}}</h3>
        <p class='p-text'>{{$profession ?? ''}} <code> {{$occupation ?? ''}}</code></p>   
        <p class='p-text'>
            @if (isset($email) && $email!='')
                <a href="mailto:{{ $email }}" class="smaller-p" target='_blank' > Email </a>     
            @endif
            @if (isset($email) && isset($lattes) && $email!='' && $lattes!='')
                |
            @endif
            @if (isset($lattes) && $lattes!='')
                <a href="{{ $lattes }}" class="smaller-p" target='_blank'> Lattes </a>       
            @endif
        </p>
        @if(isset($github) && $github!='')
        <p>
            <a href={{$github}} class="smaller-p">
                <img src="/img/github-mark.svg" alt="Github logo" style="width:20px;">
                Github
            </a>
        </p>
        @endif

    </div>
</div>