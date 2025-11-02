<script>
    import Modal from './Modal.svelte'

    let {
        show = false,
        maxWidth = '2xl',
        closeable = true,
        title,
        content,
        footer,
        close
    } = $props();
</script>

<Modal {show} {maxWidth} {closeable} {close}>
    <div class="px-6 py-4">
        <div class="text-lg dark:text-white">
            {@render title?.()}
        </div>

        <div class="mt-4 dark:text-white">
            {@render content?.()}
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 gap-x-3 bg-gray-100 text-right border-t border-gray-200 dark:bg-gray-950 dark:border-gray-800">
        {@render footer?.()}
    </div>
</Modal>
