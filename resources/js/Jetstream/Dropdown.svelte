<script>
    export let align = 'right';
    export let width = '48';
    export let contentClasses = ['py-1', 'bg-white'];
    let open = false;

    function widthClass() {
        return {
            '48': 'w-48',
        }[width.toString()]
    }

    function alignmentClasses() {
        if (align === 'left') {
            return 'origin-top-left left-0'
        } else if (align === 'right') {
            return 'origin-top-right right-0'
        } else {
            return 'origin-top'
        }
    }
</script>

<div class="relative">
    <div on:click={() => open = !open}>
        <slot name="trigger"/>
    </div>

    <!-- Full Screen Dropdown Overlay -->
    {#if open}
        <div class="fixed inset-0 z-40" on:click={() => open = false}>
        </div>
    {/if}

    {#if open}
        <div class={[widthClass(), alignmentClasses(), 'absolute z-50 mt-2 rounded-md shadow-lg'].join(' ')}
             on:click={() => open = false}>
            <div class="rounded-md ring-1 ring-black ring-opacity-5">
                <slot name="content"/>
            </div>
        </div>
    {/if}
</div>
