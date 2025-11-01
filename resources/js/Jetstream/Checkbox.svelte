<script>
    import clsx from "clsx";

    let {checked = $bindable(), ...props} = $props();
</script>

<input type="checkbox" bind:checked {...props}
       class={clsx('rounded border-gray-300 text-primary-500 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50', props.class)}>
