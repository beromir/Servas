<script>
    import {isUndefined} from "lodash";

    export let message;
</script>

{#if !isUndefined(message)}
    <p class={['mt-1 text-sm text-red-600', $$restProps.class].join(' ').trim()}>
        {message}
    </p>
{/if}
