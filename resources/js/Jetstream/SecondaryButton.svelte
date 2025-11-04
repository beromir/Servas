<script>
    let { type = 'button', children, clicked } = $props();
</script>

<button {type} onclick={() => clicked()}
        class="inline-flex items-center px-4 py-2 bg-white border border-gray-400 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-xs hover:bg-gray-50 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">
    {@render children?.()}
</button>
