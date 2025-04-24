<div data-simplebar class="h-100">
    <div id="sidebar-menu">
        <ul class="metismenu list-unstyled" id="side-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard*') ? 'bg-light active' : '' }}">
                    <i class="mdi mdi-home"></i><span>Dashboard</span>
                </a>
            </li>

            @if(in_array(auth()->user()->role, ['Super Admin', 'Admin']))
                <li class="menu-title mt-2" data-key="t-menu">Configuration</li>
                <li>
                    <a href="{{ route('user.index') }}" class="{{ request()->is('user*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-account-supervisor"></i><span>{{ __('messages.mng_user') }}</span>
                    </a>
                </li>
                @if(auth()->user()->role == 'Super Admin')
                <li>
                    <a href="{{ route('rule.index') }}" class="{{ request()->is('rule*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-cog-box"></i><span>{{ __('messages.mng_rule') }}</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('dropdown.index') }}" class="{{ request()->is('dropdown*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-package-down"></i><span>{{ __('messages.mng_dropdown') }}</span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-menu">Master</li>
                <li>
                    <a href="{{ route('priority.index') }}" class="{{ request()->is('priority*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-list-status"></i><span>{{ __('messages.priority') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('category.index') }}" class="{{ request()->is('category*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-sitemap-outline"></i><span>{{ __('messages.category') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('subcategory.index') }}" class="{{ request()->is('subcategory*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-sitemap"></i><span>{{ __('messages.sub_category') }}</span>
                    </a>
                </li>
            @endif

            <li class="menu-title mt-2" data-key="t-menu">Helpdesk</li>
            <li>
                <a href="{{ route('createTicket.index') }}" class="{{ request()->is('create-ticket*') ? 'bg-light active' : '' }}">
                    <i class="mdi mdi-ticket-outline"></i><span>{{ __('messages.ticket_create') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('ticket.index') }}" class="{{ request()->is('ticket*') ? 'bg-light active' : '' }}">
                    <i class="mdi mdi-ticket-confirmation"></i><span>{{ __('messages.ticket_list') }}</span>
                </a>
            </li>

            @if(in_array(auth()->user()->role, ['Super Admin', 'Admin']))
                <li class="menu-title mt-2" data-key="t-menu">{{ __('messages.other') }}</li>
                <li>
                    <a href="{{ route('auditlog.index') }}" class="{{ request()->is('auditlog*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-chart-donut"></i><span>{{ __('messages.audit_log') }}</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>