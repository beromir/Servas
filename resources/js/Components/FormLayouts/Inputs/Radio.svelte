<script>
    export let id;
    export let name;
    export let value;
    export let currentValue;
    export let label;
</script>

<div class="flex items-center">
    <input type="radio" on:change={(event) => currentValue = event.target.checked ? value : currentValue}
           {id} {name} {value} checked={value === currentValue} {...$$restProps}
           class="text-primary-600 focus:ring-primary-600 dark:bg-gray-800 dark:border-gray-600 dark:focus:ring-offset-gray-800">
    <label for={id} class="block ml-2 text-sm text-gray-600 capitalize dark:text-gray-300">{label}</label>
</div>
