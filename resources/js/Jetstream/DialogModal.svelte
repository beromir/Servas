<script>
    import {createEventDispatcher} from 'svelte';
    import Modal from './Modal.svelte'

    const dispatch = createEventDispatcher();

    export let show = false;
    export let maxWidth = '2xl';
    export let closeable = true;
</script>

<Modal {show} {maxWidth} {closeable} on:close={dispatch('close')}>
    <div class="px-6 py-4">
        <div class="text-lg dark:text-white">
            <slot name="title"/>
        </div>

        <div class="mt-4 dark:text-white">
            <slot name="content"/>
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 gap-x-3 bg-gray-100 text-right border-t border-gray-200 dark:bg-gray-950 dark:border-gray-800">
        <slot name="footer"/>
    </div>
</Modal>
