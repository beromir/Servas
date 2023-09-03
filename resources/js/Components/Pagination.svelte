<script>
    import {Link, router} from '@inertiajs/svelte';

    export let prevPageUrl = '';
    export let nextPageUrl = '';
    export let currentPage = 0;
    export let totalPages = 0;
</script>

<nav class="flex flex-col justify-center items-center mt-8 px-4 sm:flex-row sm:justify-end sm:px-0" aria-label="Pagination">
    <div class="order-last mt-4 text-sm text-gray-600 font-medium sm:order-none sm:mt-0 sm:mr-4">
        {currentPage} of {totalPages}
    </div>
    <div class="flex bg-white ring-1 ring-[#DFDFDF] divide-x divide-gray-100 shadow-sm rounded-md overflow-hidden">
        <Link href={prevPageUrl} aria-disabled={!prevPageUrl}
              class="inline-flex items-center px-5 py-3 text-gray-500 hover:bg-gray-50 aria-disabled:text-gray-200">
            <span class="sr-only">Previous</span>
            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                <path d="M13.125 16.25L6.875 10L13.125 3.75" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        </Link>

        <Link href={nextPageUrl} aria-disabled={!nextPageUrl}
              class="inline-flex items-center px-5 py-3 text-gray-500 hover:bg-gray-50 aria-disabled:text-gray-200">
            <span class="sr-only">Next</span>
            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                <path d="M7.375 3.75L13.625 10L7.375 16.25" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        </Link>
    </div>
</nav>
