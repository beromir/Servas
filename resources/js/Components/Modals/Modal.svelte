<script>
    import clsx from "clsx";

    let {
        size = 'normal',
        showFooterMenuOnMobile = true,
        title = '',
        mobilePrimaryAction,
        children,
        footer,
    } = $props();

    let dialog;

    const getSizeClasses = () => {
        switch (size) {
            case 'normal':
                return 'sm:max-w-lg';
            case 'xl':
                return 'sm:max-w-xl md:max-w-2xl lg:max-w-3xl'
        }
    };

    export function show() {
        dialog.showModal();

        // Safari mobile incorrectly shows :focus-visible on programmatic focus
        // Only blur on touch devices to preserve keyboard accessibility
        if ('ontouchstart' in window && document.activeElement) {
            document.activeElement.blur();
        }
    }

    export function close() {
        dialog.close();
    }

    function handleDialogClick(e) {
        if (e.target === dialog) close();
    }
</script>

<dialog bind:this={dialog} onclick={handleDialogClick}
        class={clsx('mt-auto text-left rounded-t-3xl shadow-xl ring-contrast backdrop:bg-gray-500/75 sm:m-auto sm:rounded-2xl dark:backdrop:bg-gray-900/75', getSizeClasses())}>
    <div class="relative flex flex-col max-h-[calc(100dvh-3rem)] sm:pt-6 sm:max-h-none dark:bg-gray-900">
        <div
            class="absolute inset-x-0 top-0 z-10 grid grid-cols-5 items-end px-4 pt-4 pb-3 backdrop-blur-3xl sm:static sm:px-6 sm:py-0 sm:backdrop-blur-none">
            <button onclick={close} type="button"
                    class="text-left text-primary-600 sm:hidden dark:text-gray-100">
                Cancel
            </button>
            <h3 class="col-span-3 text-lg leading-6 text-center font-semibold text-gray-800 truncate sm:text-left dark:text-white">
                {title}
            </h3>
            {#if mobilePrimaryAction}{@render mobilePrimaryAction()}{:else}
                <div></div>
            {/if}
        </div>
        <div class="grow pt-16 overflow-y-auto sm:pt-5">
            <div class="px-4 pb-4 sm:px-6">
                {@render children?.()}
            </div>

            <div
                class={clsx(showFooterMenuOnMobile ? 'grid' : 'hidden', 'gap-x-4 gap-y-3 px-4 py-3 bg-gray-100 border-t border-gray-200 overflow-auto sm:flex sm:justify-end sm:px-6 dark:bg-gray-950 dark:border-gray-800')}>
                {@render footer?.()}
            </div>

            <div
                class={clsx('h-[env(safe-area-inset-bottom)] sm:hidden', showFooterMenuOnMobile ? 'bg-gray-100 dark:bg-gray-950' : '')}></div>
        </div>
    </div>
</dialog>
