<script>
    import {isUndefined} from "lodash";

    let { message, ...rest } = $props();
</script>

{#if !isUndefined(message)}
    <p class={['mt-1 text-sm text-red-600 dark:text-red-300', rest.class].join(' ').trim()}>
        {message}
    </p>
{/if}
