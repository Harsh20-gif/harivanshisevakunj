@props(['crumbs' => [], 'active' => ''])

<nav class="admin-breadcrumbs" aria-label="Breadcrumb">
    <a href="{{ route('admin.dashboard') }}" class="admin-breadcrumbs__link">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        <span>Admin</span>
    </a>

    @foreach ($crumbs as $crumb)
        <span class="admin-breadcrumbs__separator" aria-hidden="true">/</span>
        @if (!empty($crumb['url']))
            <a href="{{ $crumb['url'] }}" class="admin-breadcrumbs__link">{{ $crumb['label'] }}</a>
        @else
            <span class="admin-breadcrumbs__link">{{ $crumb['label'] }}</span>
        @endif
    @endforeach

    @if (!empty($active))
        <span class="admin-breadcrumbs__separator" aria-hidden="true">/</span>
        <span class="admin-breadcrumbs__current" aria-current="page">{{ $active }}</span>
    @endif
</nav>
