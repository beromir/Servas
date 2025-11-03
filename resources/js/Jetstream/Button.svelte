<script>
    import clsx from "clsx";

    let {type = 'button', clicked, children, ...props} = $props();
</script>

<button {type} onclick={clicked} {...props}
        class={clsx('inline-flex items-center px-4 py-2 bg-primary-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-600 focus:outline-hidden focus:border-primary-200 focus:ring-3 focus:ring-primary-200 disabled:opacity-25 transition', props.class)}>
    {@render children?.()}
</button>
