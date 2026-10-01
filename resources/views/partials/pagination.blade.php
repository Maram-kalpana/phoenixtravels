@if ($packages->lastPage() > 1)
<nav class="pagination-nav mt-4">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ ($packages->currentPage() == 1) ? 'disabled' : '' }}">
            <a class="page-link" href="javascript:void(0)" data-page="{{ $packages->currentPage() - 1 }}" aria-label="Previous">
                <span aria-hidden="true"><i class="fa-solid fa-chevron-left"></i></span>
            </a>
        </li>
        
        @for ($i = 1; $i <= $packages->lastPage(); $i++)
            <li class="page-item {{ ($packages->currentPage() == $i) ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)" data-page="{{ $i }}">{{ $i }}</a>
            </li>
        @endfor
        
        <li class="page-item {{ ($packages->currentPage() == $packages->lastPage()) ? 'disabled' : '' }}">
            <a class="page-link" href="javascript:void(0)" data-page="{{ $packages->currentPage() + 1 }}" aria-label="Next">
                <span aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
        </li>
    </ul>
</nav>
@endif