<script>
    import clsx from "clsx";

    let {checked = $bindable(), ...props} = $props();
</script>

<input type="checkbox" bind:checked {...props}
       class={clsx('rounded-sm border-gray-300 text-primary-500 shadow-xs focus:border-primary-300 focus:ring-3 focus:ring-primary-200 focus:ring-opacity-50', props.class)}>
