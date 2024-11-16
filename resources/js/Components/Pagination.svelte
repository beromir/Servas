<script>
    import {Link} from '@inertiajs/svelte';

    export let prevPageUrl = '';
    export let nextPageUrl = '';
    export let currentPage = 0;
    export let totalPages = 0;
    export let totalLinks = 0;

    let showLinksCount = false;

    function toggleLinksCount() {
        showLinksCount = true;

        setTimeout(() => {
            showLinksCount = false;
        }, 1500);
    }
</script>

<nav class="flex flex-col justify-center items-center mt-8 px-4 sm:flex-row sm:justify-end sm:px-0"
     aria-label="Pagination">
    <button on:click={toggleLinksCount} type="button" title={`${totalLinks} links in total`}
            class="order-last mt-4 text-sm text-gray-600 font-medium sm:order-none sm:mt-0 sm:mr-4 dark:text-gray-300">
        {showLinksCount ? `${totalLinks} links in total` : `${currentPage} of ${totalPages}`}
    </button>
    <div class="flex bg-white ring-1 ring-gray-300 divide-x divide-gray-200 shadow-sm rounded-md overflow-hidden dark:bg-gray-800 dark:divide-gray-700 dark:ring-contrast">
        <Link href={prevPageUrl} aria-disabled={!prevPageUrl}
              class="inline-flex items-center px-5 py-3 text-gray-500 hover:bg-gray-50 aria-disabled:text-gray-200 aria-disabled:pointer-events-none dark:text-gray-200 dark:hover:bg-gray-700 dark:aria-disabled:text-gray-600">
            <span class="sr-only">Previous</span>
            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-5">
                <path d="M13.125 16.25L6.875 10L13.125 3.75" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        </Link>

        <Link href={nextPageUrl} aria-disabled={!nextPageUrl}
              class="inline-flex items-center px-5 py-3 text-gray-500 hover:bg-gray-50 aria-disabled:text-gray-200 aria-disabled:pointer-events-none dark:text-gray-200 dark:hover:bg-gray-700 dark:aria-disabled:text-gray-600">
            <span class="sr-only">Next</span>
            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-5">
                <path d="M7.375 3.75L13.625 10L7.375 16.25" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        </Link>
    </div>
</nav>
