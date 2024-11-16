<script>
    export let id = null;
    export let value;
    export let type = 'text';

    let input;

    export const focus = () => input.focus();
    export const select = () => input.select();

    $: props = {
        ...$$restProps,
        class: `border-gray-400 focus:border-primary-500 focus:ring focus:ring-primary-400 focus:ring-opacity-50 rounded-md shadow-sm dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600 ${$$restProps.class || ''}`,
    };

    function update(event) {
        value = event.target.value;
    }
</script>

<input {...props} bind:this={input} {id} {type} {value} on:input={update}/>
