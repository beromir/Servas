<script>
    let {hasErrors = false, ...props} = $props();
</script>

{#if hasErrors}
    <div {...props}>
        <div class="font-medium text-red-600 dark:text-red-300">Whoops! Something went wrong.</div>
    </div>
{/if}
