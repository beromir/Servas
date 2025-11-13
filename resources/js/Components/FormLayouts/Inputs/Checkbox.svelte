<script>
    let {
        name,
        label,
        error = null,
        checked = $bindable(false),
        ...rest
    } = $props();
</script>

<div class="flex items-center">
    <input id={name} {name} type="checkbox" bind:checked {...rest}
           class="size-4 rounded-sm border-gray-300 ring-0! ring-offset-0! focus:outline-2 focus:outline-primary-500 checked:bg-primary-600 checked:border-primary-600 dark:bg-gray-900 dark:border-gray-500">
    <label for={name} class="ml-2 block text-sm text-gray-900 dark:text-gray-200">{label}</label>
</div>

{#if error}
    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-500">{error}</div>
{/if}
