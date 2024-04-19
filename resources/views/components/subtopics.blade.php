<ol id="topic-{{$topic->id}}-subtopics">
    @foreach ($subtopics as $subtopic)
        <li class="mb-3" id="topic-{{ $subtopic->id }}">
            <span class="topic-title" style="font-weight: normal;">{{ $subtopic->title }}</span>

            <a 
                class="ml-3 expand-topic" 
                data-topic_id="{{ $subtopic->id }}" 
                style="cursor: pointer; font-size: 14px;"
            >
                Mostrar mais
            </a>

            <br>

            @if ($subtopic->required_level)
                <small> Domínio desejado: {{  $subtopic->required_level }}</small>
            @endif
        </li>
    @endforeach
</ol>