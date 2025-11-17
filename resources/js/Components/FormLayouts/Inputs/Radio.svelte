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
           class="border-gray-400 ring-0! ring-offset-0! focus-visible:outline-2 focus-visible:outline-primary-500 checked:bg-primary-600 checked:border-primary-600 dark:bg-gray-950 dark:border-gray-500">
    <label for={id} class="block ml-2 text-sm text-gray-600 capitalize dark:text-gray-300">{label}</label>
</div>
