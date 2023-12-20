<script>
    export let name;
    export let label;
    export let error = null;
    export let checked = false;
</script>

<div class="flex items-center">
    <input id={name} {name} type="checkbox" bind:checked {...$$restProps}
           class="size-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
    <label for={name} class="ml-2 block text-sm text-gray-900">{label}</label>
</div>

{#if error}
    <div class="text-yellow-800">{error}</div>
{/if}
