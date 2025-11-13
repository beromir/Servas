<script>
    let {
        name,
        label,
        type = 'text',
        value = $bindable(null),
        ...rest
    } = $props();

    let input = $state();
    export const focus = () => input.focus()
</script>

<label for={name} class="sr-only">{label}</label>
<input id={name} {type} {value} {...rest} bind:this={input} oninput={(e) => value = e.target.value}
       class={['shadow-xs block w-full sm:text-sm border-gray-400 rounded-md focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600', rest.class].join(' ').trim()}>
