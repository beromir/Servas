<script>
    export let name;
    export let label;
    export let type = 'text';
    export let value = null;
    export let error = null;
</script>

<div>
    <label for={name}
           class="block text-sm text-left font-medium text-gray-700 dark:text-gray-200">
        {label}
    </label>
    <div class="mt-1">
        <input id={name} {name} {type} {...$$restProps} {value} on:input={(e) => value = e.target.value}
               class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm dark:text-gray-50 dark:bg-gray-900 dark:border-gray-500">
        {#if error}
            <div class="text-yellow-800">{error}</div>
        {/if}
    </div>
</div>
