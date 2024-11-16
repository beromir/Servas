<script>
    import {createEventDispatcher} from 'svelte';
    import Modal from './Modal.svelte'

    const dispatch = createEventDispatcher();

    export let show = false;
    export let maxWidth = '2xl';
    export let closeable = true;
</script>

<Modal {show} {maxWidth} {closeable} on:close={dispatch('close')}>
    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-900">
        <div class="sm:flex sm:items-start">
            <div
                class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10 dark:bg-red-300">
                <svg class="size-6 text-red-600 dark:text-red-800" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left dark:text-white">
                <h3 class="text-lg">
                    <slot name="title"/>
                </h3>

                <div class="mt-2">
                    <slot name="content"/>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-row justify-end gap-x-3 px-6 py-4 bg-gray-100 text-right border-t border-gray-200 dark:bg-gray-950 dark:border-gray-800">
        <slot name="footer"/>
    </div>
</Modal>
