<script>
    export let name;
    export let label;
    export let isFirst = false;
    export let type = 'text';
    export let value = null;
    export let error = null;
</script>

<div class={['sm:grid sm:grid-cols-5 sm:gap-4 sm:items-start',
    !isFirst ? 'sm:border-t sm:border-gray-200 sm:pt-5 dark:border-gray-700' : '',
    $$restProps.class].join(' ').trim()}>
    <label for={name}
           class="block text-sm text-left font-medium text-gray-700 sm:mt-px sm:pt-2 dark:text-gray-200">
        {label}
    </label>
    <div class="mt-1 sm:mt-0 sm:col-span-4">
        <input id={name} {name} {type} {...$$restProps} {value} on:input={(e) => value = e.target.value}
               class="block max-w-xl w-full shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-400 rounded-md dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600">
        {#if error}
            <div class="text-yellow-800">{error}</div>
        {/if}
    </div>
</div>
