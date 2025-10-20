@extends('layouts.admin')

@section('title', 'Utilizadores')

@section('css')
<link rel="stylesheet" href="/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active">Utilizadores</li>
@endsection

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if(auth()->user()->hasPermission('addUsers'))
                        <a href="{{ route('admin.users.create') }}" class="btn btn-default">Adicionar
                            Utilizador</a>
                        @endif
                    </div>
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Papeis</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="/lte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/lte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

<script src="../assets/admin/js/users/index.js"></script>
<script src="../assets/js/allIndex.js"></script>

@endsection