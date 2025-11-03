<script>
    import clsx from "clsx";

    let {id = null, value = $bindable(), type = 'text', ...props} = $props();

    let input;

    export const focus = () => input.focus();
    export const select = () => input.select();

    function update(event) {
        value = event.target.value;
    }
</script>

<input bind:this={input} {id} {type} {value} oninput={update} {...props}
       class={clsx('border-gray-400 focus:border-primary-500 focus:ring-3 focus:ring-primary-400 focus:ring-opacity-50 rounded-md shadow-xs dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600', props.class)}/>
