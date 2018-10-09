<ul class="list-unstyled components">
    <li>
        <a href="/">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
    </li>
    <!-- Administração -->
    @can('administracao')
        <li>
            <a href="#pageSubmenuAdm" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-university"></i>Administração</a>
            <ul class="collapse list-unstyled" id="pageSubmenuAdm">
                @can('grupo')
                    <li>
                        <a href="/master/grupos"> <i class="fas fa-shapes"></i>Grupos</a>
                    </li>
                @endcan
                @can('usuario')
                    <li>
                        <a href="/master/usuarios"> <i class="fas fa-users-cog"></i>Usuários</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan
        <!-- Alunos -->
    @can('aluno')
        <li class="nav-item">
            <a class="nav-link" href="{{route('alunos.index')}}"><i class="fas fa-user-graduate"></i>Aluno</a>
        </li>
    @endcan
</ul>