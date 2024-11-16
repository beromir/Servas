<script>
    // export let errors;
    export let hasErrors = false;

    $: props = {
        ...$$restProps,
    };
</script>

{#if hasErrors}
    <div {...props}>
        <div class="font-medium text-red-600 dark:text-red-300">Whoops! Something went wrong.</div>

        <!--
        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li>{errors}</li>
        </ul>
        -->
    </div>
{/if}
