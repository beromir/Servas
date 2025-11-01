<script>
    import clsx from "clsx";

    let {id, label, ...props} = $props();
</script>

{#if label}
    <label {...props} for={id} class={clsx('block font-medium text-sm text-gray-700 dark:text-gray-200', props.class)}>
        {label}
    </label>
{/if}
