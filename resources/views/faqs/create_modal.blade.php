<div class="modal fade" id="faqs-create" tabindex="-1" role="dialog" aria-labelledby="FAQ Create" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registrar FAQ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('disciplinas.faqs.store', $discipline->id)}}">
            <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="title" class="col-form-label">
                            Pergunta <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control"
                               id="title" name="title" value="{{old('title')}}">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content" class="col-form-label">
                            Resposta <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="content" name="content">{{old('content')}}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
            </form>
        </div>
    </div>
</div>
