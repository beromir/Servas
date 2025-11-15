<script>
    import Checkbox from "@/Components/Checkbox.svelte";

    let {
        name,
        label,
        error = null,
        checked = $bindable(false),
        ...rest
    } = $props();
</script>

<div class="flex items-center">
    <Checkbox id={name} {name} bind:checked {...rest}/>
    <label for={name} class="ml-2 block text-sm text-gray-900 dark:text-gray-200">{label}</label>
</div>

{#if error}
    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-500">{error}</div>
{/if}
