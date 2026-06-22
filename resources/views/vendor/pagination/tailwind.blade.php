@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;justify-content:center;gap:0.5rem;margin-top:2rem;">
    @if ($paginator->onFirstPage())
        <span style="width:38px;height:38px;border-radius:50%;background:var(--cream-dark);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:0.875rem;">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="width:38px;height:38px;border-radius:50%;background:white;box-shadow:var(--shadow);display:flex;align-items:center;justify-content:center;color:var(--brown);text-decoration:none;font-size:0.875rem;transition:var(--transition);">‹</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="color:var(--text-muted);">...</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="width:38px;height:38px;border-radius:50%;background:var(--orange);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.875rem;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="width:38px;height:38px;border-radius:50%;background:white;box-shadow:var(--shadow);display:flex;align-items:center;justify-content:center;color:var(--brown);text-decoration:none;font-weight:600;font-size:0.875rem;">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="width:38px;height:38px;border-radius:50%;background:white;box-shadow:var(--shadow);display:flex;align-items:center;justify-content:center;color:var(--brown);text-decoration:none;font-size:0.875rem;">›</a>
    @else
        <span style="width:38px;height:38px;border-radius:50%;background:var(--cream-dark);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:0.875rem;">›</span>
    @endif
</nav>
@endif