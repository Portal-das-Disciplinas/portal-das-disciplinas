@extends('layouts.app')

@section('title')
    Painel de Metodologias - Portal das Disciplinas
@endsection


@section('description')
    Painel de Metodologias
@endsection

@section('robots')
    noindex, follow
@endsection

@section('styles-head')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
@endsection

@section('content')
    <div class="container" style="min-height: 60vh;">
        <div class='page-title'>
            <h1>Painel de Metodologias</h1>
        </div>

        <div class="row mb-4">
            <div class="col-12 col-sm-6 col-lg-3 mt-2 mb-2">
                <button class="btn btn-block btn-primary" id="btn-open-modal" data-toggle="modal" data-target="#modal-cadastro-metodologia">
                    Cadastrar metodologia
                </button>

                <div id="modal-cadastro-metodologia" class="modal large fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Cadastro de metodologias</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div id="collapse-criar-metodologia" class="" class="collapse form-group">
                                                <label class="text-secondary" for="nome-nova-metodologia">Nome da
                                                    metodologia</label>
                                                <input id="nome-nova-metodologia" type="text" class="form-control mb-1">
                                                <label class="text-secondary" for="descricao-nova-metodologia">Descrição da
                                                    metodologia</label>
                                                <textarea id="descricao-nova-metodologia" class="form-control mb-1" rows="6"></textarea>
                                                <p>
                                                    <small id="feedback-cadastro-methodology"
                                                        class="d-none text-success form-text">Metodologia
                                                        adicionada</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="btn-close-modal-add-methodologies" type="button"
                                    class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button class="btn btn-sm btn-primary" id="btn-save-methodology">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive" id="methodologies-container">
            <table class="table" id="methodologies-table" style='background-color: #fff; border-radius: 8px;'>
                <thead>
                    <tr>
                        <th class="text-primary">Metodologia</th>
                        <th class="text-primary">Editar</th>
                        <th class="text-primary">Apagar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($methodologies as $methodology)
                        <tr>
                            <td>{{ $methodology->name }}</td>
                            <td>
                                <button type="button" class="btn text-success border-success edit-methodology" title="Editar metodologia" data-methodology="{{ json_encode($methodology) }}">
                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn text-danger border-danger delete-methodology" title="Deletar metodologia" data-target="{{ $methodology->id }}">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td>Não há metodologias cadastradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <li>{{ $error }}</li>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
        @endif


    </div>
@endsection

@section('scripts-bottom')
    <script>
        function clearInputs() {
            methodologiesHandler.inputs.name.val('');
            methodologiesHandler.inputs.description.val('');
        }

        const btnSave = $('#btn-save-methodology');
        const btnOpenModal = $('#btn-open-modal');
        const token = '{{ csrf_token() }}';
        const methodologyModal = $('#modal-cadastro-metodologia');

        const methodologiesHandler = {
            inputs: {
                name: $('#nome-nova-metodologia'),
                description: $('#descricao-nova-metodologia'),
                professor_id: null
            },
            store: function() {
                let btnText = btnSave.innerHTML;
                btnSave.innerHTML = "Cadastrando metodologia";
                btnSave.disabled = true;

                let methodology = {
                    name: methodologiesHandler.inputs.name.val(),
                    description: methodologiesHandler.inputs.description.val(),
                }

                $.ajax({
                    url: '/metodologias/store',
                    method: 'post',
                    data: {
                        '_token': token,
                        'methodology': methodology
                    },
                    success: function (data) {
                        let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');
                        feedbackRegisterMethodology.innerHTML = 'Metodologia cadastrada';
                        feedbackRegisterMethodology.classList.remove('d-none');
                        feedbackRegisterMethodology.classList.remove('text-danger');
                        feedbackRegisterMethodology.classList.add('text-success');

                        btnSave.disabled = false;
                        btnSave.innerHTML = btnText;

                        clearInputs();

                        $('#btn-close-modal-add-methodologies').click();
                        $('#methodologies-container').load(document.URL +  ' #methodologies-table');
                        feedbackRegisterMethodology.classList.add('d-none');
                    },
                    error: function (xhr, status, error) {
                        let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');
                        feedbackRegisterMethodology.classList.remove('text-success');
                        feedbackRegisterMethodology.classList.add('text-danger');
                        feedbackRegisterMethodology.classList.remove('d-none');

                        let jsonError = JSON.parse(xhr.responseText);

                        if (jsonError) {
                            feedbackRegisterMethodology.innerHTML = jsonError.error;
                        } else {
                            feedbackRegisterMethodology.innerHTML = jsonError.error;
                        }

                        btnSave.disabled = false;
                        btnSave.innerHTML = btnText;
                    }
                });
            },
            destroy: function() {
                let canDelete = confirm("Tem certeza que deseja deletar essa metodologia ? Ao realizar esta operação, essa metodologia será apagada de todas as páginas que contém esta metodologia.");

                if (!canDelete) {
                    return;
                }

                const methodologyId = $(this).data('target');

                $.ajax({
                    url: '/metodologias/delete/' + methodologyId,
                    method: 'delete',
                    data: {
                        '_token': token,
                    },
                    complete: function() {
                        $('#methodologies-container').load(document.URL +  ' #methodologies-table');
                    }
                });
            },
            update: function() {
                if (selectedMethodology === null) {
                    return;
                }

                let newName = methodologiesHandler.inputs.name.val();
                let newDescription = methodologiesHandler.inputs.description.val();

                let feedbackAlertDiv = document.querySelector('#feedback-methodology');
                let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');

                $.ajax({
                    url: '/metodologias/update/' + selectedMethodology.id,
                    method: 'PUT',
                    data: {
                        '_token': token,
                        'name': newName,
                        'description': newDescription
                    },
                    success: function (data) {
                        let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');
                        feedbackRegisterMethodology.innerHTML = 'Metodologia atualizada!';
                        feedbackRegisterMethodology.classList.remove('d-none');
                        feedbackRegisterMethodology.classList.remove('text-danger');
                        feedbackRegisterMethodology.classList.add('text-success');

                        $('#methodologies-container').load(document.URL +  ' #methodologies-table');
                        feedbackRegisterMethodology.classList.add('d-none');

                    },
                    error: function (xhr, status, error) {
                        let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');
                        feedbackRegisterMethodology.classList.remove('text-success');
                        feedbackRegisterMethodology.classList.add('text-danger');
                        feedbackRegisterMethodology.classList.remove('d-none');

                        feedbackRegisterMethodology.innerHTML = "Erro ao atualizar.";
                    }
                });
            }
        };

        btnOpenModal.click(function() {
            clearInputs();

            methodologyModal.data('op', 'create');
        });

        let selectedMethodology = null;

        methodologyModal.on('shown.bs.modal', function() {
            let operation = $(this).data('op');

            btnSave.off("click");

            if (operation === "create") {
                btnSave.click(methodologiesHandler.store);
            } else if (operation === "update") {
                btnSave.click(methodologiesHandler.update);
            }
        });

        
        $('.table-responsive').on('click', '.delete-methodology', methodologiesHandler.destroy);
        $('.table-responsive').on('click', '.edit-methodology', function() {
            methodologyModal.data('op', 'update');

            selectedMethodology = $(this).data('methodology');
                
            methodologiesHandler.inputs.name.val(selectedMethodology.name);
            methodologiesHandler.inputs.description.val(selectedMethodology.description);

            methodologyModal.modal('show');
        });
    </script>
@endsection
