@extends('layouts.app')

@section('title')
Painel de Administração - Portal das Disciplinas
@endsection

@section('description')
Painel de Administração
@endsection

@section('content')


<div class="container">
    <div class="page-title">
        <h1>Painel de administração</h1>
    </div>
    <div class="register-teacher-container" style='min-height: 60vh'>
        <div class="row">
            <div class="col-12 col-sm-6 col-lg-3 mt-2 mb-2">
                 <a name="createProfessor" class="btn btn-block btn-primary"
                   href="{{ route('professores.create') }}" role="button">Cadastrar professor</a>
            </div>
        </div>
        <div style='background-color: #fff; border-radius: 8px;'>
            <div class="table-responsive card-body pb-0">
                <table id="dynamic-table" class="table">
                    <thead>
                        <tr>
                            <th scope="col"><a class="sortable" data-column="name">Nome</th>
                            <th scope="col"><a class="sortable" data-column="email">Email</a></th>
                            <th scope="col"><a class="sortable" data-column="public_email">Email Público</a></th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($professors as $i => $professor)
                            <tr>
                                <td>{{$professor->name}}</td>
                                <td>{{$professor->user->email}}</td>
                                <td>{{$professor->public_email}}</td>
                                <td>
                                    <div class="form-group">
                                        <form action="{{route('professores.destroy',$professor->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="Deletar" class="btn btn-danger btn-block">
                                        </form>
                                    </div>
                                    <form action="{{route('professores.edit', $professor->id)}}" method="GET">
                                        @csrf
                                        @method('GET')
                                        <input type="submit" value="Atualizar" class="btn btn-block">
                                    </form>
                                    <!-- {{-- <div class="form-group">
                                        <form action="" method="POST">
                                            <input type="submit" value="Redefinir Senha" class="btn btn-primary btn-block">
                                        </form>
                                    </div> --}} -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        sortTable(0, 'asc');

        $('.sortable').click(function() {
            var column = $(this).index();
            var order = $(this).hasClass('asc') ? 'desc' : 'asc';

            $('.sortable').removeClass('asc desc');
            $(this).addClass(order);

            sortTable(column, order);
        });

        function sortTable(column, order) {
            var table = $('#dynamic-table');
            var tbody = table.find('tbody');
            var rows = tbody.find('tr').toArray();

            rows.sort(function(a, b) {
                var aValue = $(a).find('td:eq(' + column + ')').text();
                var bValue = $(b).find('td:eq(' + column + ')').text();

                if ($.isNumeric(aValue) && $.isNumeric(bValue)) {
                    aValue = parseFloat(aValue);
                    bValue = parseFloat(bValue);
                }

                if (order === 'asc') {
                    return aValue > bValue ? 1 : -1;
                } else {
                    return aValue < bValue ? 1 : -1;
                }
            });

            tbody.empty();
            $.each(rows, function(index, row) {
                tbody.append(row);
            });
        }
    });
</script>

@endsection
