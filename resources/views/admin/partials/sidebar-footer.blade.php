<div class="admin-sidebar__footer">
    <a href="{{ route('home') }}" class="admin-sidebar__footer-btn admin-sidebar__footer-btn--store">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        View Storefront
    </a>
    <form method="POST" action="{{ route('logout') }}" class="admin-sidebar__logout-form">
        @csrf
        <button type="submit" class="admin-sidebar__footer-btn admin-sidebar__footer-btn--logout">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Logout
        </button>
    </form>
</div>
