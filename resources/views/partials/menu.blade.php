<div class="sidebar">
    <nav class="sidebar-nav">

        <ul class="nav">
            @can('user_management_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-users nav-icon">

                        </i>
                        {{ trans('cruds.userManagement.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('permission_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                                    </i>
                                    {{ trans('cruds.permission.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-briefcase nav-icon">

                                    </i>
                                    {{ trans('cruds.role.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('user_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user nav-icon">

                                    </i>
                                    {{ trans('cruds.user.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('insurance_company_access')
                <li class="nav-item">
                    <a href="{{ route("admin.insuranceCompany.index") }}" class="nav-link {{ request()->is('admin/insurance_company') || request()->is('admin/insurance_company/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-user-tie nav-icon">

                        </i>
                        Insurance Company
                    </a>
                </li>
            @endcan
            @can('agent_access')
                <li class="nav-item">
                    <a href="{{ route("admin.agents.index") }}" class="nav-link {{ request()->is('admin/agents') || request()->is('admin/agents/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-user-tie nav-icon">

                        </i>
                        Agent
                    </a>
                </li>
            @endcan
            @can('company_access')
                <li class="nav-item">
                    <a href="{{ route("admin.companies.index") }}" class="nav-link {{ request()->is('admin/companies') || request()->is('admin/companies/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-building nav-icon">

                        </i>
                        {{ trans('cruds.company.title') }}
                    </a>
                </li>
            @endcan
            @can('insurance_access')
                <li class="nav-item">
                    <a href="{{ route("admin.insurances.index") }}" class="nav-link {{ request()->is('admin/insurances') || request()->is('admin/insurances/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-list nav-icon">

                        </i>
                        {{ trans('cruds.insurance.title') }}
                    </a>
                </li>
            @endcan
            @can('attachment_access')
                <li class="nav-item">
                    <a href="{{ route("admin.attachments.index") }}" class="nav-link {{ request()->is('admin/attachments') || request()->is('admin/attachments/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-paperclip nav-icon">

                        </i>
                        Attachment
                    </a>
                </li>
            @endcan            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>