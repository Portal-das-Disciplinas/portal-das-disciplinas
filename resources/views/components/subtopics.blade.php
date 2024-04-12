<ul id="topic-{{$topic->id}}-subtopics">
    @foreach ($subtopics as $subtopic)
        <li class="mb-3" id="topic-{{ $subtopic->id }}">
            <span class="topic-title">{{ $subtopic->title }}</span>

            <a 
                class="ml-3 expand-topic" 
                data-topic_id="{{ $subtopic->id }}" 
                style="cursor: pointer; font-size: 14px;"
            >
                Mostrar mais
            </a>

            <br>

            @if ($subtopic->required_level)
                <small> Nível desejado: {{  $subtopic->required_level }}</small>
            @endif
        </li>
    @endforeach
</ul>