<script>
    let { type = 'button', children, clicked = () => {}, ...rest } = $props();
</script>

<button {type} onclick={() => clicked()} {...rest}
        class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-600 disabled:opacity-25 transition">
    {@render children?.()}
</button>
