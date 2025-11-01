<script>
    let {
        id,
        name,
        value,
        currentValue = $bindable(),
        label,
        ...rest
    } = $props();
</script>

<div class="flex items-center">
    <input type="radio" onchange={(event) => currentValue = event.target.checked ? value : currentValue}
           {id} {name} {value} checked={value === currentValue} {...rest}
           class="text-primary-600 focus:ring-primary-600 dark:bg-gray-800 dark:border-gray-600 dark:focus:ring-offset-gray-800">
    <label for={id} class="block ml-2 text-sm text-gray-600 capitalize dark:text-gray-300">{label}</label>
</div>
