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
       class={clsx('border-gray-400 rounded-md shadow-xs dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600', props.class)}/>
