<ol id="topic-{{$topic->id}}-subtopics">
    @forelse ($subtopics as $subtopic)
        <li class="mb-3" id="topic-{{ $subtopic->id }}">
            <span class="topic-title" style="font-weight: normal;">{{ $subtopic->title }}</span>

            @if (count($subtopic->subtopics) > 0 && Route::currentRouteName() != "disciplines.edit")
                <a 
                    class="ml-3 expand-topic" 
                    data-topic_id="{{ $subtopic->id }}" 
                    style="cursor: pointer; font-size: 14px;"
                >
                    Subtópicos
                </a>
            @else
                <a 
                    class="ml-3 expand-topic" 
                    data-topic_id="{{ $topic->id }}" 
                    style="cursor: pointer; font-size: 14px;"
                >
                    Mostrar mais
                </a>
            @endif

            <br>

            @if ($subtopic->required_level)
                <small> Domínio desejado: <span class="topic-level">{{  $subtopic->required_level }}</span></small>
            @endif
        </li>
    @empty
        <small>Sem subtópicos cadastrados</small>
    @endforelse
</ol>