<script>
    export let name;
    export let label;
    export let type = 'text';
    export let value = null;
    export let error = null;
</script>

<div>
    <label for={name}
           class="block text-sm text-left font-medium text-gray-700 sm:mt-px sm:pt-2">
        {label}
    </label>
    <div class="mt-1">
        <input id={name} {name} {type} {...$$restProps} {value} on:input={(e) => value = e.target.value}
               class="block w-full shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 rounded-md">
        {#if error}
            <div class="text-yellow-800">{error}</div>
        {/if}
    </div>
</div>
