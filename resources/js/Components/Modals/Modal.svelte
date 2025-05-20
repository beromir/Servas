<script>
    import {noScroll} from "@/utils";
    import {fade, scale} from 'svelte/transition';
    import {backIn, backOut} from 'svelte/easing';
    import {createEventDispatcher} from "svelte";
    import clsx from "clsx";

    export let showModal;
    export let size = 'normal';
    export let showFooterMenuOnMobile = true;
    export let title = '';

    const dispatch = createEventDispatcher();

    const getSizeClasses = () => {
        switch (size) {
            case 'normal':
                return 'sm:max-w-lg';
            case 'xl':
                return 'sm:max-w-xl md:max-w-2xl lg:max-w-3xl'
        }
    };

    function handleEsc(event) {
        if (showModal && event.key === 'Escape') {
            event.preventDefault();
            showModal = false;
            dispatch('canceled');
        }
    }
</script>

<svelte:window on:keydown|stopPropagation={handleEsc}/>

{#if showModal}
    <div {...$$restProps} class="relative z-[100]">
        <!-- Background overlay -->
        <div in:fade={{duration: 300, easing: backOut}}
             out:fade={{duration: 200, easing: backIn}}
             class="fixed inset-0 bg-gray-500/75 transition-opacity dark:bg-gray-900/75"
             aria-hidden="true"></div>

        <div class="fixed inset-0 w-full sm:overflow-y-auto">
            <!-- svelte-ignore a11y-click-events-have-key-events a11y-no-static-element-interactions -->
            <div on:click={() => {showModal = false; dispatch('canceled')}}
                 class="flex min-h-full justify-center items-end sm:items-center sm:max-h-none">
                <div on:click|stopPropagation use:noScroll
                     in:scale={{duration: 300, start: 0.95, easing: backOut}}
                     out:scale={{duration: 200, start: 0.95, easing: backIn}}
                     class={clsx('mt-12 w-full bg-white text-left rounded-t-3xl shadow-xl overflow-hidden transform transition-all ring-contrast sm:my-8 sm:rounded-2xl', getSizeClasses())}
                     aria-hidden="true">
                    <div
                        class="flex flex-col max-h-[calc(100dvh-3rem)] bg-white sm:pt-6 sm:max-h-none dark:bg-gray-900">
                        <div
                            class="fixed inset-x-0 top-0 z-10 grid grid-cols-5 items-end px-4 pt-4 pb-3 backdrop-blur-3xl sm:static sm:px-6 sm:py-0 sm:backdrop-blur-none">
                            <button on:click={() => {showModal = false; dispatch('canceled')}}
                                    class="text-left text-primary-600 focus:outline-none sm:hidden dark:text-gray-100"
                                    type="button">
                                Cancel
                            </button>
                            <h3 class="col-span-3 text-lg leading-6 text-center font-semibold text-gray-800 truncate sm:text-left dark:text-white">
                                {title}
                            </h3>
                            <slot name="mobilePrimaryAction">
                                <div></div>
                            </slot>
                        </div>
                        <div class="flex-grow pt-16 overflow-y-auto sm:pt-5">
                            <div class="px-4 pb-4 sm:px-6">
                                <slot/>
                            </div>

                            <div
                                class={clsx(showFooterMenuOnMobile ? 'grid' : 'hidden', 'gap-x-4 gap-y-3 px-4 py-3 bg-gray-100 border-t border-gray-200 overflow-auto sm:flex sm:justify-end sm:px-6 dark:bg-gray-950 dark:border-gray-800')}>
                                <slot name="footer"/>
                            </div>

                            <div
                                class={clsx('h-[env(safe-area-inset-bottom)] sm:hidden', showFooterMenuOnMobile ? 'bg-gray-100 dark:bg-gray-950' : '')}></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}
