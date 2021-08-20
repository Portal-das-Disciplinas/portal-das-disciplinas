@extends('layouts.app')

@section('title')
    Taxa de Aprovação das Disciplinas - Portal das Disciplinas IMD
@endsection

@section('description')
    Aqui você conseguirá ver a taxa de aprovação das disciplinas.
    Foram disponibilizados dois tipos de visualizações: por docente e por componente curricular.
@endsection

@section('styles-head')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('libs/select2/dist/css/select2.min.css')}}">
@endsection

@section('content')
    <div class="row info-portal">
        <div class="col-md-12 mb-3">
            <h3>Taxa de Aprovação das Disciplinas</h3>
            <p class="mt-4 span-info text-justify">
                Aqui você conseguirá ver a taxa de aprovação das disciplinas.
                Foram disponibilizados dois tipos de visualizações: por docente e por componente curricular.
                <br/>
                <br/>
                Para saber mais sobre o algoritmo que gerou essas taxas,
                <a href="https://github.com/alvarofpp/analysis-ufrn">clique aqui</a>.
            </p>
        </div>
        <div class="col-md-12 mb-3">
            <h4>Visualização por Docente</h4>
            <p class="mt-4 span-info text-justify">
                Aqui você pode buscar por um docente e visualizar a sua taxa de aprovação para cada disciplina lecionada por ele.
            </p>
            <div class="row">
                <div class="col-md-12 form-group mb-2">
                    <label for="select_professor">
                        Docente
                    </label>
                    <select class="form-control select2"
                            id="select_professor" name="select_professor"></select>
                </div>
                <div class="col-md-12">
                    <table class="table text-white">
                        <thead>
                        <tr>
                            <th scope="col">Nome do componente curricular</th>
                            <th scope="col">Taxa de aprovação (%)</th>
                        </tr>
                        </thead>
                        <tbody id="tbody_professor">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <h4>Visualização por Componente Curricular</h4>
            <p class="mt-4 span-info text-justify">
                Aqui você pode buscar pelo componente curricular e visualizar a taxa de aprovação por docente que já lecionou esse componente.
            </p>
            <div class="row">
                <div class="col-md-12 form-group mb-3">
                    <label for="select_discipline">
                        Componente Curricular
                    </label>
                    <select class="form-control select2"
                            id="select_discipline" name="select_discipline"></select>
                </div>
                <div class="col-md-12">
                    <table class="table text-white">
                        <thead>
                        <tr>
                            <th scope="col">Nome do docente</th>
                            <th scope="col">Taxa de aprovação (%)</th>
                        </tr>
                        </thead>
                        <tbody id="tbody_discipline">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts-bottom')
    <!-- Select2 -->
    <script src="{{asset('libs/select2/dist/js/select2.full.min.js')}}"></script>

    <script>
        $('#select_professor').select2({
            placeholder: 'Selecione',
            ajax: {
                url: '{{ route('charts.pass_rate.professors') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data.data, function (item, key) {
                            return {
                                text: item,
                                id: key
                            }
                        }),
                        pagination: {
                            more: (params.page * 30) < data.total
                        }
                    };
                },
                cache: true,
                minimumInputLength: 1,
            }
        }).on('select2:select', function (e) {
            var data = e.params.data;
            const tbody = $('#tbody_professor');
            tbody.html('');

            $.get('{{route('charts.pass_rate.tables')}}', {
                type: 'professor',
                id: data.id
            }, function (rows) {
                rows.forEach((element, index, array) => {
                    let rowHtml = '<tr><td>' + element.nome_componente + '</td><td>' + Number(element.taxa_aprovacao).toFixed(2) + '</td></tr>';
                    tbody.append(rowHtml);
                });
            }).fail(function (data) {
                console.log(data);
            });
        });

        $('#select_discipline').select2({
            placeholder: 'Selecione',
            ajax: {
                url: '{{ route('charts.pass_rate.disciplines') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data.data, function (item, key) {
                            return {
                                text: item,
                                id: key
                            }
                        }),
                        pagination: {
                            more: (params.page * 30) < data.total
                        }
                    };
                },
                cache: true,
                minimumInputLength: 1,
            }
        }).on('select2:select', function (e) {
            var data = e.params.data;
            const tbody = $('#tbody_discipline');
            tbody.html('');

            $.get('{{route('charts.pass_rate.tables')}}', {
                type: 'discipline',
                id: data.id
            }, function (rows) {
                rows.forEach((element, index, array) => {
                    let rowHtml = '<tr><td>' + element.nome_docente + '</td><td>' + Number(element.taxa_aprovacao).toFixed(2) + '</td></tr>';
                    tbody.append(rowHtml);
                });
            }).fail(function (data) {
                console.log(data);
            });
        });
    </script>
@endsection
