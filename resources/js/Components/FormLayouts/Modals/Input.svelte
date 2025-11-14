<script>
    import Input from "@/Components/Input.svelte";

    let {
        name,
        label,
        isFirst = false,
        type = 'text',
        value = $bindable(null),
        error = null,
        ...rest
    } = $props();
</script>

<div class={['sm:grid sm:grid-cols-5 sm:gap-4 sm:items-start',
    !isFirst ? 'sm:border-t sm:border-gray-200 sm:pt-5 dark:border-gray-700' : '',
    rest.class].join(' ').trim()}>
    <label for={name}
           class="block text-sm text-left font-medium text-gray-700 sm:mt-px sm:pt-2 dark:text-gray-200">
        {label}
    </label>
    <div class="mt-1 sm:mt-0 sm:col-span-4">
        <Input id={name} {name} {type} bind:value {...rest}/>

        {#if error}
            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-500">{error}</div>
        {/if}
    </div>
</div>
