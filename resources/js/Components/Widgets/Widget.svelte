<script>
    let {
        title = '',
        withBackground = true,
        fixedHeight = false,
        children
    } = $props();
</script>

<div>
    <h3 class="font-medium text-gray-700">{title}</h3>
    <div class={[
         'relative mt-3 overflow-hidden',
         withBackground ? 'bg-white shadow-sm rounded-lg' : '',
         fixedHeight ? 'h-120' : '',
         ].join(' ').trim()}>
        {@render children?.()}
    </div>
</div>
