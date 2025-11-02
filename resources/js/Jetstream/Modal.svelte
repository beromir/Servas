<script>
    let {
        show = false,
        maxWidth = '2xl',
        closeable = true,
        close,
        children
    } = $props();

    function maxWidthClass() {
        return {
            'sm': 'sm:max-w-sm',
            'md': 'sm:max-w-md',
            'lg': 'sm:max-w-lg',
            'xl': 'sm:max-w-xl',
            '2xl': 'sm:max-w-2xl',
        }[maxWidth]
    }
</script>

{#if show}
    <div class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50">
        <div class="fixed inset-0 transform transition-all" onclick={() => {if (closeable) close()}} aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500/75 dark:bg-gray-900/75"></div>
        </div>

        <div
            class={['mb-6 bg-white rounded-lg overflow-hidden shadow-xl ring-contrast transform transition-all sm:w-full sm:mx-auto dark:bg-gray-900', maxWidthClass()].join(' ').trim()}>
            {@render children?.()}
        </div>
    </div>
{/if}
