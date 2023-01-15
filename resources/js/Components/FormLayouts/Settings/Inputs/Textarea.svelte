<script>
    export let name;
    export let label;
    export let value = null;
    export let placeholder;
    export let description;
    export let error = null;
</script>

<div>
    <label for={name} class="block text-sm font-medium text-gray-700">{label}</label>
    <div class="mt-1">
                    <textarea id={name} {name} {value} {...$$restProps} on:input={(e) => value = e.target.value}
                              rows="3"
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                              {placeholder}></textarea>
    </div>
    {#if description}
        <p class="mt-2 text-sm text-gray-500">{description}</p>
    {/if}
    {#if error}
        <div class="text-yellow-800">{error}</div>
    {/if}
</div>
