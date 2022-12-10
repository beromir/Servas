<script>
    import {noScroll} from "@/utils";
    import {fade, scale} from 'svelte/transition';
    import {backIn, backOut} from 'svelte/easing';

    export let showModal;
    export let size = 'normal';
    export let showFooterMenuOnMobile = true;
    export let title = '';
    export let fullWidthContent = false

    const getSizeClasses = () => {
        switch (size) {
            case 'normal':
                return 'sm:max-w-lg';
            case 'xl':
                return 'sm:max-w-xl md:max-w-2xl lg:max-w-3xl'
        }
    };

    function handleEsc(event) {
        if (event.key === 'Escape') {
            event.preventDefault();
            showModal = false;
        }
    }
</script>

{#if showModal}
    <div {...$$restProps} on:keydown|stopPropagation={handleEsc}
         class="fixed z-10 inset-0 overflow-y-auto"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-12 text-center sm:block sm:p-0">
            <div on:click={() => showModal = false}
                 in:fade={{duration: 300, easing: backOut}}
                 out:fade={{duration: 200, easing: backIn}}
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div on:click|stopPropagation use:noScroll
                 in:scale={{duration: 300, start: 0.95, easing: backOut}}
                 out:scale={{duration: 200, start: 0.95, easing: backIn}}
                 class={['inline-block align-bottom w-full bg-white text-left overflow-hidden rounded-t-xl shadow-xl transform transition-all sm:my-8 sm:align-middle sm:rounded-lg',
                 getSizeClasses()].join(' ').trim()}
                 aria-hidden="true">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="grid grid-cols-5 items-end sm:block sm:items-center">
                        <button on:click={() => showModal = false}
                                class="text-left text-primary-600 focus:outline-none sm:hidden" type="button">
                            Cancel
                        </button>
                        <h3 class="col-span-3 text-lg leading-6 text-center font-medium text-gray-900 sm:text-left">
                            {title}
                        </h3>
                        <slot name="mobilePrimaryAction">
                            <div></div>
                        </slot>
                    </div>
                    <div class="mt-5" class:-mx-4={fullWidthContent} class:sm:-mx-6={fullWidthContent}>
                        <slot/>
                    </div>
                </div>
                <div class={[showFooterMenuOnMobile ? 'flex flex-col' : 'hidden sm:flex',
                     'bg-gray-50 px-4 py-3 sm:flex-row-reverse sm:px-6'].join(' ').trim()}>
                    <slot name="footer"/>
                </div>
            </div>
        </div>
    </div>
{/if}
