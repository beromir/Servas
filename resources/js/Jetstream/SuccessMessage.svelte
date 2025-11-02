<script>
    let { show = false, message = '' } = $props();
</script>

{#if show}
    <div class="mr-4 text-sm text-green-700 dark:text-green-400">
        {message}
    </div>
{/if}
