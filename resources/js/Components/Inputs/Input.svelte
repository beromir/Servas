<script>
    export let name;
    export let label;
    export let type = 'text';
    export let value = null;

    let input;
    export const focus = () => input.focus()
</script>

<label for={name} class="sr-only">{label}</label>
<input id={name} {type} {value} {...$$restProps} bind:this={input} on:input={(e) => value = e.target.value}
       class={['shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md', $$restProps.class].join(' ').trim()}>
