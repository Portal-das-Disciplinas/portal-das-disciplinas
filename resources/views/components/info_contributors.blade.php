<div class='d-flex flex-column  align-items-center mx-4 mb-3' style="width:250px"><!-- mx-4 mb-3-->
    <img class="clip-path regular-image" src="{{'/storage/'.$image}}" alt="{{ $alt_image ?? 'foto-dev' }}" height="160px"style="object-fit:contain;background-color: #146C94;" onerror="loadDefault(event)">
    <div class="text-center">
        <h3>{{$name ?? ''}}</h3>
        @if(isset($period) && ($period!='null'))
        <small style="line-height:1" class="mt-0 p-0 text-secondary">{{$period}}</small>
        @endif
        <p class='p-text'>{{$profession ?? ''}} <code> {{$occupation ?? ''}}</code></p>   
        <p class='p-text'>
            @if (isset($email) && $email!='')
                <a href="mailto:{{ $email }}" class="smaller-p" rel='noopener' target='_blank'> Email </a>     
            @endif
            @if (isset($email) && isset($lattes) && $email!='' && $lattes!='')
                |
            @endif
            @if (isset($lattes) && $lattes!='')
                <a href="{{ $lattes }}" class="smaller-p" rel='noopener' target='_blank'> Lattes </a>       
            @endif
        </p>
        @if(isset($links))
            {{$links}}
        @endif
        @if(isset($github) && $github!='')
        <p>
            <a href={{$github}} class="smaller-p" rel='noopener' target='_blank'>
                <img src="/img/github-mark.svg" alt="Github logo" style="width:20px;">
                Github
            </a>
        </p>
        @endif

    </div>
    <script>
        function loadDefault(event){

            event.target.style= "background-color: #FFFFFF00";
            event.target.src = 'img/profiles_img/user2.png';
        }
    </script>
</div>