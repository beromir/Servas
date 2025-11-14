<script>
    import Input from "@/Components/Input.svelte";

    let {
        name,
        label,
        type = 'text',
        value = $bindable(null),
        error = null,
        ...rest
    } = $props();
</script>

<div>
    <label for={name}
           class="block text-sm text-left font-medium text-gray-700 dark:text-gray-200">
        {label}
    </label>
    <div class="mt-1">
        <Input id={name} {name} {type} bind:value {...rest} class="dark:bg-gray-900"/>

        {#if error}
            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-500">{error}</div>
        {/if}
    </div>
</div>
