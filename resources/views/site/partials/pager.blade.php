@if ($items->hasPages())
    <nav class="staff-pages" aria-label="صفحه‌ها">
        @if ($items->onFirstPage())
            <span>قبلی</span>
        @else
            <a href="{{ $items->previousPageUrl() }}">قبلی</a>
        @endif
        <span>{{ $items->currentPage() }} از {{ $items->lastPage() }}</span>
        @if ($items->hasMorePages())
            <a href="{{ $items->nextPageUrl() }}">بعدی</a>
        @else
            <span>بعدی</span>
        @endif
    </nav>
@endif
